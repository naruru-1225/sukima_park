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
        // 許可する拡張子リスト
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'heic'];

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:MEMBER_TABLE,EMAIL',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]+$/',
                'confirmed',
            ],
            'tel' => 'nullable|string|max:20',
            'birth' => 'nullable|date',
            'gender' => 'nullable|integer|in:0,1,2',
            'identification' => 'required|file|max:5120', // 5MB以下
        ]);

        // 本人確認書類の処理
        $identityPath = null;
        if ($request->hasFile('identification')) {
            $file = $request->file('identification');
            $originalExtension = strtolower($file->getClientOriginalExtension());

            // 拡張子チェック
            if (!in_array($originalExtension, $allowedExtensions)) {
                return back()->withErrors([
                    'identification' => '許可されていないファイル形式です。jpeg, jpg, png, heicのみアップロード可能です。',
                ])->withInput();
            }

            // ファイル名生成（ユニークなファイル名）
            $fileName = uniqid('identity_') . '_' . time();

            // HEIC形式の場合はJPGに変換
            if ($originalExtension === 'heic') {
                $identityPath = $this->convertHeicToJpg($file, $fileName);
            } else {
                // その他の形式はそのまま保存（拡張子は小文字に統一）
                $newExtension = ($originalExtension === 'jpeg') ? 'jpg' : $originalExtension;
                $identityPath = $file->storeAs('identifications', $fileName . '.' . $newExtension, 'public');
            }
        }

        $member = Member::create([
            'USERNAME' => $request->username,
            'EMAIL' => $request->email,
            'PASSWORD' => Hash::make($request->password),
            'TEL' => $request->tel,
            'BIRTH' => $request->birth,
            'GENDER' => $request->gender ?? 0,
            'SHOW_BIRTH' => false,
            'SHOW_GENDER' => false,
            'IDENTITY' => $identityPath ? true : false,
            'IDENTITY_IMAGE' => $identityPath,
            'ICON_IMAGE' => 'default_icon.png',
            'ACCOUNT_STATUS' => 1,
        ]);

        Auth::login($member);

        return redirect('/')->with('success', '会員登録が完了しました！');
    }

    /**
     * HEIC形式の画像をJPGに変換
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $fileName
     * @return string 保存されたファイルパス
     */
    private function convertHeicToJpg($file, $fileName)
    {
        $destinationPath = storage_path('app/public/identifications');
        
        // ディレクトリが存在しない場合は作成
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $outputPath = $destinationPath . '/' . $fileName . '.jpg';

        // ImageMagickを使用してHEICをJPGに変換
        if (extension_loaded('imagick')) {
            $imagick = new \Imagick();
            $imagick->readImage($file->getPathname());
            $imagick->setImageFormat('jpg');
            $imagick->setImageCompressionQuality(85);
            $imagick->writeImage($outputPath);
            $imagick->destroy();
        } else {
            // ImageMagickがない場合はコマンドラインで変換を試みる
            $inputPath = $file->getPathname();
            $command = "magick convert \"{$inputPath}\" \"{$outputPath}\"";
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                // 変換に失敗した場合はエラー
                throw new \Exception('HEIC画像の変換に失敗しました。ImageMagickがインストールされているか確認してください。');
            }
        }

        return 'identifications/' . $fileName . '.jpg';
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
