<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Affiliate;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\UserMeta;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

    use UserFormFieldsTrait;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/panel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $seoSettings = getSeoMetas('register');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.register_page_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.register_page_title');
        $pageRobot = getPageRobot('register');

        $referralSettings = getReferralSettings();

        $referralCode = Cookie::get('referral_code');

        $formFields = $this->getFormFieldsByUserType($request, 'user', true);

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'referralCode' => $referralCode,
            'referralSettings' => $referralSettings,
            'formFields' => $formFields
        ];

        return view(getTemplate() . '.auth.register', $data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
{
    $registerMethod = getGeneralSettings('register_method') ?? 'mobile';

    // normalize mobile for validation copy
    if (!empty($data['mobile']) && !empty($data['country_code'])) {
        $data['mobile'] = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');
    }

    $rules = [
        'country_code' => ($registerMethod == 'mobile') ? 'required' : 'nullable',
        'mobile'       => (($registerMethod == 'mobile') ? 'required' : 'nullable') . '|numeric|unique:users',
        'email'        => (($registerMethod == 'email') ? 'required' : 'nullable') . '|email|max:255|unique:users',
        'term'         => 'required',
        'full_name'    => 'required|string|min:3',
        'password'     => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required|same:password',
        'referral_code' => 'nullable|exists:affiliates_codes,code',

        // NEW optional profile fields
        'sex'                        => 'nullable|in:male,female,other',
        'birthdate'                  => 'nullable|date',
        'nationality'                => 'nullable|string|max:255',
        'religion'                   => 'nullable|string|max:255',
        'nid_number'                 => 'nullable|string|max:255',
        'address'                    => 'nullable|string|max:255',
        'fathers_name'               => 'nullable|string|max:255',
        'fathers_email'              => 'nullable|email|max:255',
        'fathers_contact_number'     => 'nullable|string|max:255',
        'mothers_name'               => 'nullable|string|max:255',
        'mothers_email'              => 'nullable|email|max:255',
        'mothers_contact_number'     => 'nullable|string|max:255',

        // Images: accept real files
        'user_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB
        'nid_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096', // 4MB
    ];

    if (!empty(getGeneralSecuritySettings('captcha_for_register'))) {
        $rules['captcha'] = 'required|captcha';
    }

    // Use request()->all() so file rules work properly
    return Validator::make(request()->all(), $rules, [], [
        'mobile'                    => trans('auth.mobile'),
        'email'                     => trans('auth.email'),
        'term'                      => trans('update.terms'),
        'full_name'                 => trans('auth.full_name'),
        'password'                  => trans('auth.password'),
        'password_confirmation'     => trans('auth.password_repeat'),
        'referral_code'             => trans('financial.referral_code'),

        // labels for new fields (optional to localize later)
        'sex'                       => 'Sex',
        'birthdate'                 => 'Birthdate',
        'nationality'               => 'Nationality',
        'religion'                  => 'Religion',
        'nid_number'                => 'NID Number',
        'address'                   => 'Address',
        'fathers_name'              => "Father's Name",
        'fathers_email'             => "Father's Email",
        'fathers_contact_number'    => "Father's Contact Number",
        'mothers_name'              => "Mother's Name",
        'mothers_email'             => "Mother's Email",
        'mothers_contact_number'    => "Mother's Contact Number",
        'user_image'                => 'User Image',
        'nid_image'                 => 'NID Image',
    ]);
}

/**
 * Create a new user instance after a valid registration.
 *
 * Note: handles both uploaded files and string paths (if you keep a file manager).
 */
protected function create(array $data)
{
    // Normalize mobile (DB value)
    if (!empty($data['mobile']) && !empty($data['country_code'])) {
        $data['mobile'] = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');
    }

    $referralSettings = getReferralSettings();
    $usersAffiliateStatus = (!empty($referralSettings) && !empty($referralSettings['users_affiliate_status']));

    if (empty($data['timezone'])) {
        $data['timezone'] = getGeneralSettings('default_time_zone') ?? null;
    }

    $disableViewContentAfterUserRegister = getFeaturesSettings('disable_view_content_after_user_register');
    $accessContent = !((!empty($disableViewContentAfterUserRegister) && $disableViewContentAfterUserRegister));

    // Role decide (default user; treat "Student" as user)
    $roleName = Role::$user;
    $roleId   = Role::getUserRoleId();
    if (!empty($data['account_type'])) {
        if ($data['account_type'] == Role::$teacher) {
            $roleName = Role::$teacher;
            $roleId   = Role::getTeacherRoleId();
        } elseif ($data['account_type'] == Role::$organization) {
            $roleName = Role::$organization;
            $roleId   = Role::getOrganizationRoleId();
        } else {
            // e.g., "Student" -> keep default user
            $roleName = Role::$user;
            $roleId   = Role::getUserRoleId();
        }
    }

    // Handle uploaded images
    $userImagePath = null;
    $nidImagePath  = null;

    if (request()->hasFile('user_image')) {
        $userImagePath = '/storage/' . request()->file('user_image')->store('users', 'public');
    } elseif (!empty($data['user_image']) && is_string($data['user_image'])) {
        // allow string paths from a file manager
        $userImagePath = $data['user_image'];
    }

    if (request()->hasFile('nid_image')) {
        $nidImagePath = '/storage/' . request()->file('nid_image')->store('nid', 'public');
    } elseif (!empty($data['nid_image']) && is_string($data['nid_image'])) {
        $nidImagePath = $data['nid_image'];
    }

    $user = User::create([
        'role_name'      => $roleName,
        'role_id'        => $roleId,
        'mobile'         => $data['mobile'] ?? null,
        'email'          => $data['email'] ?? null,
        'full_name'      => $data['full_name'],
        'status'         => User::$pending,
        'access_content' => $accessContent,
        'password'       => Hash::make($data['password']),
        'affiliate'      => $usersAffiliateStatus,
        'timezone'       => $data['timezone'] ?? null,
        'created_at'     => time(),

        // NEW columns
        'sex'                     => $data['sex'] ?? null,
        'birthdate'               => $data['birthdate'] ?? null,
        'nationality'             => $data['nationality'] ?? null,
        'religion'                => $data['religion'] ?? null,
        'nid_number'              => $data['nid_number'] ?? null,
        'address'                 => $data['address'] ?? null,
        'fathers_name'            => $data['fathers_name'] ?? null,
        'fathers_email'           => $data['fathers_email'] ?? null,
        'fathers_contact_number'  => $data['fathers_contact_number'] ?? null,
        'mothers_name'            => $data['mothers_name'] ?? null,
        'mothers_email'           => $data['mothers_email'] ?? null,
        'mothers_contact_number'  => $data['mothers_contact_number'] ?? null,
        'user_image'              => $userImagePath,
        'nid_image'               => $nidImagePath,
    ]);

    if (!empty($data['certificate_additional'])) {
        UserMeta::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'certificate_additional'],
            ['value'   => $data['certificate_additional']]
        );
    }

    // keep your dynamic form fields behavior
    $this->storeFormFields($data, $user);

    return $user;
}


}
