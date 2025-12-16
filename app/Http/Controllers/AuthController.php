<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * ログインフォーム表示
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // メールアドレスでユーザーを検索
        $member = Member::where('EMAIL', $request->email)->first();

        if ($member && Hash::check($request->password, $member->PASSWORD)) {
            // アカウントステータスのチェック
            if ($member->ACCOUNT_STATUS == 1) {
                return back()->withErrors([
                    'email' => 'このアカウントは凍結されています。',
                ]);
            }

            Auth::login($member, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->onlyInput('email');
    }

    /**
     * 会員登録フォーム表示
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * 会員登録処理
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:MEMBER_TABLE,EMAIL',
            'password' => 'required|string|min:8|confirmed',
            'tel' => 'nullable|string|max:20',
            'birth' => 'nullable|date',
            'gender' => 'nullable|integer|in:0,1,2',
        ]);

        $member = Member::create([
            'USERNAME' => $request->username,
            'EMAIL' => $request->email,
            'PASSWORD' => Hash::make($request->password),
            'TEL' => $request->tel,
            'BIRTH' => $request->birth,
            'GENDER' => $request->gender ?? 0,
            'SHOW_BIRTH' => false,
            'SHOW_GENDER' => false,
            'IDENTITY' => false,
            'ICON_IMAGE' => 'default_icon.png',
            'ACCOUNT_STATUS' => 0,
        ]);

        Auth::login($member);

        return redirect('/')->with('success', '会員登録が完了しました！');
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'ログアウトしました。');
    }
}
