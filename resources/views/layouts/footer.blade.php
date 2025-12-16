<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            {{-- サイト情報 --}}
            <div class="footer-section">
                <h3 class="footer-title">
                    <span class="logo-icon">🏞️</span>
                    スキマパーク
                </h3>
                <p class="footer-description">
                    使っていない土地を有効活用。<br>
                    あなたのスキマ、誰かの価値に。
                </p>
            </div>
            
            {{-- サービス --}}
            <div class="footer-section">
                <h4 class="footer-subtitle">サービス</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/lands') }}">土地を探す</a></li>
                    <li><a href="{{ url('/lands/create') }}">土地を登録する</a></li>
                    <li><a href="{{ url('/about') }}">スキマパークとは</a></li>
                </ul>
            </div>
            
            {{-- サポート --}}
            <div class="footer-section">
                <h4 class="footer-subtitle">サポート</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/contact') }}">お問い合わせ</a></li>
                    <li><a href="{{ url('/faq') }}">よくある質問</a></li>
                    <li><a href="{{ url('/guide') }}">ご利用ガイド</a></li>
                </ul>
            </div>
            
            {{-- 法的情報 --}}
            <div class="footer-section">
                <h4 class="footer-subtitle">法的情報</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/terms') }}">利用規約</a></li>
                    <li><a href="{{ url('/privacy') }}">プライバシーポリシー</a></li>
                    <li><a href="{{ url('/legal') }}">特定商取引法に基づく表記</a></li>
                </ul>
            </div>
        </div>
        
        {{-- コピーライト --}}
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} スキマパーク All Rights Reserved.</p>
        </div>
    </div>
</footer>
