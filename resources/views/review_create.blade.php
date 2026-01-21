@extends('layouts.app')

@section('title', 'レビュー投稿')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 20px;">レビューを投稿</h1>

    {{-- 土地情報 --}}
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h3>取引情報</h3>
        <p><strong>土地:</strong> {{ $rental->land->CITY ?? '' }} {{ $rental->land->STREET_ADDRESS ?? '' }}</p>
        <p><strong>面積:</strong> {{ $rental->land->AREA ?? '-' }} ㎡</p>
        <p><strong>利用期間:</strong> 
            {{ $rental->RENTAL_START_DATE ? \Carbon\Carbon::parse($rental->RENTAL_START_DATE)->format('Y/m/d') : '-' }}
            〜
            {{ $rental->RENTAL_END_DATE ? \Carbon\Carbon::parse($rental->RENTAL_END_DATE)->format('Y/m/d') : '-' }}
        </p>
    </div>

    {{-- レビューフォーム --}}
    <form action="{{ route('review.store', $rental->RECORD_ID) }}" method="POST">
        @csrf

        {{-- 土地の評価 --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">土地の評価 <span style="color: red;">*</span></label>
            <div style="display: flex; gap: 10px;">
                @for ($i = 1; $i <= 5; $i++)
                    <label style="cursor: pointer;">
                        <input type="radio" name="land_review" value="{{ $i }}" 
                            {{ old('land_review', $existingReview->LAND_REVIEW ?? '') == $i ? 'checked' : '' }}
                            required>
                        {{ $i }}
                    </label>
                @endfor
            </div>
            @error('land_review')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 土地のコメント --}}
        <div style="margin-bottom: 20px;">
            <label for="land_comment" style="display: block; margin-bottom: 8px; font-weight: bold;">土地のコメント</label>
            <textarea name="land_comment" id="land_comment" rows="4" 
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                maxlength="512">{{ old('land_comment', $existingReview->LAND_COMMENT ?? '') }}</textarea>
            @error('land_comment')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 貸し手の評価 --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">貸し手の評価 <span style="color: red;">*</span></label>
            <div style="display: flex; gap: 10px;">
                @for ($i = 1; $i <= 5; $i++)
                    <label style="cursor: pointer;">
                        <input type="radio" name="user_review" value="{{ $i }}" 
                            {{ old('user_review', $existingReview->USER_REVIEW ?? '') == $i ? 'checked' : '' }}
                            required>
                        {{ $i }}
                    </label>
                @endfor
            </div>
            @error('user_review')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 貸し手のコメント --}}
        <div style="margin-bottom: 20px;">
            <label for="user_comment" style="display: block; margin-bottom: 8px; font-weight: bold;">貸し手へのコメント</label>
            <textarea name="user_comment" id="user_comment" rows="4" 
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                maxlength="512">{{ old('user_comment', $existingReview->USER_COMMENT ?? '') }}</textarea>
            @error('user_comment')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- ボタン --}}
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('trade.detail', $rental->RECORD_ID) }}" 
               style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                キャンセル
            </a>
            <button type="submit" 
                style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                {{ $existingReview ? 'レビューを更新' : 'レビューを投稿' }}
            </button>
        </div>
    </form>
</div>
@endsection
