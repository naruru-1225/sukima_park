@extends('layouts.app')

@section('title', '会員登録')

@section('content')
<style>
      :root {
        --primary-color: #2e7d32;
        --primary-hover: #1b5e20;
        --error-color: #d32f2f;
        --border-color: #e0e0e0;
        --text-primary: #333;
        --text-secondary: #555;
        --text-hint: #888;
        --bg-page: #fafafa;
        --bg-white: #fff;
        --bg-secondary: #f5f5f5;
        --bg-hover: #e0e0e0;
        --shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        --border-radius: 6px;
        --transition: all 0.2s;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
          "Hiragino Sans", sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background: var(--bg-page);
      }

      .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
      }

      /* Header */
      header {
        background: var(--bg-white);
        border-bottom: 1px solid var(--border-color);
        position: sticky;
        top: 0;
        z-index: 100;
      }

      .header-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 60px;
      }

      .logo {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
        text-decoration: none;
      }

      .header-nav {
        display: flex;
        gap: 12px;
        align-items: center;
      }

      /* Buttons */
      .btn {
        padding: 8px 16px;
        border-radius: var(--border-radius);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
      }

      .btn-primary {
        background: var(--primary-color);
        color: var(--bg-white);
      }

      .btn-primary:hover {
        background: var(--primary-hover);
      }

      .btn-secondary {
        background: var(--bg-secondary);
        color: var(--text-primary);
      }

      .btn-secondary:hover {
        background: var(--bg-hover);
      }

      /* Form */
      .registration-section {
        padding: 40px 0;
      }

      .form-container {
        background: var(--bg-white);
        border-radius: 8px;
        padding: 32px;
        box-shadow: var(--shadow);
        max-width: 600px;
        margin: 0 auto;
      }

      .form-container h1 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #222;
        text-align: center;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 6px;
        color: var(--text-secondary);
      }

      .required {
        color: var(--error-color);
        font-size: 12px;
        margin-left: 4px;
      }

      .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.2s;
      }

      .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
      }

      .form-control.error {
        border-color: var(--error-color);
      }

      .form-control[type="file"] {
        padding: 8px 12px;
      }

      .form-control-hint {
        font-size: 12px;
        color: var(--text-hint);
        margin-top: 4px;
      }

      .error-message {
        font-size: 12px;
        color: var(--error-color);
        margin-top: 4px;
        display: none;
      }

      .error-message.show {
        display: block;
      }

      /* Radio buttons */
      .radio-group {
        display: flex;
        gap: 16px;
        margin-top: 8px;
        flex-wrap: wrap;
      }

      .radio-label {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: normal;
      }

      .radio-label input[type="radio"] {
        cursor: pointer;
        accent-color: var(--primary-color);
      }

      /* Submit button */
      .submit-btn {
        width: 100%;
        padding: 12px;
        background: var(--primary-color);
        color: var(--bg-white);
        border: none;
        border-radius: var(--border-radius);
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 16px;
        transition: var(--transition);
      }

      .submit-btn:hover:not(:disabled) {
        background: var(--primary-hover);
      }

      .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
      }

      /* Login link */
      .login-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
      }

      .login-link a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
      }

      .login-link a:hover {
        text-decoration: underline;
      }

      /* Success message */
      .success-message {
        background: #e8f5e9;
        color: var(--primary-color);
        padding: 12px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        display: none;
        text-align: center;
      }

      .success-message.show {
        display: block;
      }

      /* Responsive */
      @media (max-width: 768px) {
        .header-nav {
          gap: 8px;
        }

        .btn {
          padding: 6px 12px;
          font-size: 13px;
        }

        .form-container {
          padding: 24px 16px;
        }

        .form-container h1 {
          font-size: 20px;
        }

        .radio-group {
          flex-direction: column;
          gap: 8px;
        }
      }
    </style>

    <main>
      <section class="registration-section">
        <div class="container">
          <div class="form-container">
            <h1>会員登録</h1>

            <div class="success-message" id="successMessage">
              登録が完了しました！
            </div>

            <form id="registrationForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" novalidate>
              @csrf
              <div class="form-group">
                <label for="loginId"
                  >ログインID<span class="required">必須</span></label
                >
                <input
                  type="text"
                  id="loginId"
                  class="form-control"
                  placeholder="例: sukimapark_user"
                  required
                  pattern="[a-zA-Z0-9_]+"
                  minlength="4"
                  maxlength="20"
                  autocomplete="username"
                />
                <p class="form-control-hint">
                  4〜20文字の半角英数記号（_）が使えます
                </p>
                <p class="error-message" id="loginIdError"></p>
              </div>

              <div class="form-group">
                <label for="email"
                  >メールアドレス<span class="required">必須</span></label
                >
                <input
                  type="email"
                  id="email"
                  class="form-control"
                  placeholder="例: user@example.com"
                  required
                  autocomplete="email"
                />
                <p class="error-message" id="emailError"></p>
              </div>

              <div class="form-group">
                <label for="password"
                  >パスワード<span class="required">必須</span></label
                >
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  placeholder="8文字以上の半角英数字"
                  required
                  minlength="8"
                  autocomplete="new-password"
                />
                <p class="form-control-hint">8文字以上で設定してください</p>
                <p class="error-message" id="passwordError"></p>
              </div>

              <div class="form-group">
                <label for="passwordConfirm"
                  >パスワード（確認用）<span class="required">必須</span></label
                >
                <input
                  type="password"
                  id="passwordConfirm"
                  class="form-control"
                  placeholder="もう一度パスワードを入力してください"
                  required
                  minlength="8"
                  autocomplete="new-password"
                />
                <p class="error-message" id="passwordConfirmError"></p>
              </div>

              <div class="form-group">
                <label for="phone"
                  >電話番号<span class="required">必須</span></label
                >
                <input
                  type="tel"
                  id="phone"
                  class="form-control"
                  placeholder="例: 09012345678（ハイフンなし）"
                  required
                  pattern="[0-9]{10,11}"
                  autocomplete="tel"
                />
                <p class="form-control-hint">
                  10〜11桁の数字で入力してください
                </p>
                <p class="error-message" id="phoneError"></p>
              </div>

              <div class="form-group">
                <label for="birthdate"
                  >生年月日<span class="required">必須</span></label
                >
                <input
                  type="date"
                  id="birthdate"
                  class="form-control"
                  required
                  max=""
                  autocomplete="bday"
                />
                <p class="error-message" id="birthdateError"></p>
              </div>

              <div class="form-group">
                <label>性別<span class="required">必須</span></label>
                <div class="radio-group">
                  <label class="radio-label">
                    <input type="radio" name="gender" value="0" required />
                    <span>男性</span>
                  </label>
                  <label class="radio-label">
                    <input type="radio" name="gender" value="1" />
                    <span>女性</span>
                  </label>
                  <label class="radio-label">
                    <input type="radio" name="gender" value="2" />
                    <span>その他</span>
                  </label>
                </div>
                <p class="error-message" id="genderError"></p>
              </div>

              <div class="form-group">
                <label for="identification"
                  >本人確認書類<span class="required">必須</span></label
                >
                <input
                  type="file"
                  id="identification"
                  name="identification"
                  class="form-control"
                  required
                  accept=".jpg,.jpeg,.png,.heic"
                />
                <p class="form-control-hint">
                  jpeg, jpg, png, heic形式の画像（最大5MB）
                </p>
                <p class="error-message" id="identificationError"></p>
              </div>

              <button type="submit" class="submit-btn">登録する</button>
            </form>

            <div class="login-link">
              すでにアカウントをお持ちですか？
              <a href="{{ route('login') }}">ログインはこちら</a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <script>
      // 生年月日の最大値を今日に設定
      const today = new Date().toISOString().split("T")[0];
      document.getElementById("birthdate").setAttribute("max", today);

      const form = document.getElementById("registrationForm");
      const errorMessages = {
        loginId: document.getElementById("loginIdError"),
        email: document.getElementById("emailError"),
        password: document.getElementById("passwordError"),
        passwordConfirm: document.getElementById("passwordConfirmError"),
        phone: document.getElementById("phoneError"),
        birthdate: document.getElementById("birthdateError"),
        gender: document.getElementById("genderError"),
        identification: document.getElementById("identificationError"),
      };

      function showError(field, message) {
        const input =
          document.getElementById(field) ||
          document.querySelector(`[name="${field}"]`);
        const errorElement = errorMessages[field];

        if (input) input.classList.add("error");
        if (errorElement) {
          errorElement.textContent = message;
          errorElement.classList.add("show");
        }
      }

      function clearError(field) {
        const input =
          document.getElementById(field) ||
          document.querySelector(`[name="${field}"]`);
        const errorElement = errorMessages[field];

        if (input) input.classList.remove("error");
        if (errorElement) {
          errorElement.textContent = "";
          errorElement.classList.remove("show");
        }
      }

      function clearAllErrors() {
        Object.keys(errorMessages).forEach(clearError);
      }

      function validateForm() {
        clearAllErrors();
        let isValid = true;

        // ログインID
        const loginId = document.getElementById("loginId").value.trim();
        if (!loginId) {
          showError("loginId", "ログインIDを入力してください");
          isValid = false;
        } else if (!/^[a-zA-Z0-9_]{4,20}$/.test(loginId)) {
          showError("loginId", "4〜20文字の半角英数記号（_）で入力してください");
          isValid = false;
        }

        // メールアドレス
        const email = document.getElementById("email").value.trim();
        if (!email) {
          showError("email", "メールアドレスを入力してください");
          isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          showError("email", "正しいメールアドレス形式で入力してください");
          isValid = false;
        }

        // パスワード（8〜20文字、英数混合必須）
        const password = document.getElementById("password").value;
        if (!password) {
          showError("password", "パスワードを入力してください");
          isValid = false;
        } else if (password.length < 8 || password.length > 20) {
          showError("password", "パスワードは8〜20文字で入力してください");
          isValid = false;
        } else if (!/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]+$/.test(password)) {
          showError("password", "パスワードは英字と数字の両方を含めてください");
          isValid = false;
        }

        // パスワード確認
        const passwordConfirm = document.getElementById("passwordConfirm").value;
        if (!passwordConfirm) {
          showError("passwordConfirm", "パスワード（確認用）を入力してください");
          isValid = false;
        } else if (password !== passwordConfirm) {
          showError("passwordConfirm", "パスワードが一致しません");
          isValid = false;
        }

        // 電話番号
        const phone = document.getElementById("phone").value.trim();
        if (!phone) {
          showError("phone", "電話番号を入力してください");
          isValid = false;
        } else if (!/^[0-9]{10,11}$/.test(phone)) {
          showError("phone", "10〜11桁の数字で入力してください（ハイフンなし）");
          isValid = false;
        }

        // 生年月日
        const birthdate = document.getElementById("birthdate").value;
        if (!birthdate) {
          showError("birthdate", "生年月日を選択してください");
          isValid = false;
        }

        // 性別
        const gender = document.querySelector('input[name="gender"]:checked');
        if (!gender) {
          showError("gender", "性別を選択してください");
          isValid = false;
        }

        // 本人確認書類
        const identification = document.getElementById("identification");
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'heic'];
        if (!identification.files || identification.files.length === 0) {
          showError("identification", "本人確認書類をアップロードしてください");
          isValid = false;
        } else {
          const file = identification.files[0];
          const maxSize = 5 * 1024 * 1024; // 5MB
          const fileName = file.name.toLowerCase();
          const extension = fileName.split('.').pop();
          
          // 拡張子チェック
          if (!allowedExtensions.includes(extension)) {
            showError("identification", "jpeg, jpg, png, heic形式のみアップロード可能です");
            isValid = false;
          } else if (file.size > maxSize) {
            showError("identification", "ファイルサイズは5MB以下にしてください");
            isValid = false;
          }
        }

        return isValid;
      }

      // リアルタイムバリデーション（フォーカスアウト時にエラーをクリア）
      ["loginId", "email", "password", "passwordConfirm", "phone", "birthdate"].forEach((id) => {
        const input = document.getElementById(id);
        if (input) {
          input.addEventListener("blur", () => {
            if (input.value) clearError(id);
          });
        }
      });

      // フォーム送信
      form.addEventListener("submit", function (e) {
        e.preventDefault();

        if (validateForm()) {
          // 送信ボタンを無効化
          const submitBtn = form.querySelector(".submit-btn");
          submitBtn.disabled = true;
          submitBtn.textContent = "登録中...";

          // フォームを実際に送信（Laravelへ）
          this.submit();
        }
      });
    </script>
@endsection