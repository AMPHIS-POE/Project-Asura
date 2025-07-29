<div id="regex" class="section">
    <div class="container">

        <h1>
            <span class="lang-ko">정규식 생성기</span>
            <span class="lang-en">Regex Generator</span>
        </h1>

        <p>
            <span class="lang-ko">맵 모드를 클릭해 정규식을 생성하세요</span>
            <span class="lang-en">Click map mods to build a regex</span>
        </p>

        <div id="regex-sticky-anchor"></div>

        <div id="regex-fixed-container">
            <div class="regex-output-wrapper">
                <div class="regex-output-container">
                    <div class="regex-input-area">
                        <textarea id="regexOutputFinal" class="regex-textarea" readonly></textarea>
                        <div id="regex-char-counter-container" class="char-counter-style">
                            <span id="regexCharCounter">0/250</span>
                            <div id="regexCharWarningContainer" style="margin-left: 10px; display: none;">
                                <span class="lang-ko" style="color: #FFB3B3;">(길이 초과!)</span>
                                <span class="lang-en" style="color: #FFB3B3;">(Too long!)</span>
                            </div>
                        </div>
                    </div>

                    <div class="regex-buttons-wrapper">
                        <button id="copyRegexButton" class="copy-button" title="클립보드에 복사">
                            <span class="lang-ko">복사</span>
                            <span class="lang-en">Copy</span>
                        </button>
                        <button id="resetRegexButton" class="reset-button" title="선택 및 결과 초기화">
                            <span class="lang-ko">초기화</span>
                            <span class="lang-en">Reset</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-accordions-wrapper">
            <div class="accordion-container">
                <button class="accordion-toggle" id="highend-options-toggle">
                    <span class="lang-ko">고급 옵션</span>
                    <span class="lang-en">Advanced Options</span>
                </button>
                <div class="accordion-content" id="highend-options-content">
                    <div id="rule-builder-area">
                        </div>
                    
                    <div class="add-rule-group-wrapper">
                        <button id="add-rule-group-button">
                            <span class="lang-ko">OR 조건 그룹 추가</span>
                            <span class="lang-en">Add OR Condition Group</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="accordion-container">
                <button class="accordion-toggle" id="basic-options-toggle">
                    <span class="lang-ko">기본 옵션</span>
                    <span class="lang-en">Basic Options</span>
                </button>
                <div class="accordion-content" id="basic-options-content">
                    <div class="filter-container" style="padding: 20px 10px 10px; border: none; background: transparent;">
                        <div class="filter-row tier-filter-row">
                            <span style="font-weight: bold; margin-right: 15px;">
                                <span class="lang-ko">티어:</span>
                                <span class="lang-en">Tier:</span>
                            </span>
                            <label class="filter-label">
                                <input type="checkbox" id="filter-tier16" checked>
                                <span class="label-text">
                                    <span class="lang-ko">16티어 모드</span><span class="lang-en">T16 MOD</span>
                                </span>
                            </label>
                            <label class="filter-label">
                                <input type="checkbox" id="filter-tier17" checked>
                                <span class="label-text">
                                    <span class="lang-ko">17티어 모드</span><span class="lang-en">T17 MOD</span>
                                </span>
                            </label>
                        </div>
                        <div class="filter-row tier-filter-row">
                            <span style="font-weight: bold; margin-right: 15px;">
                                <span class="lang-ko">희귀도:</span>
                                <span class="lang-en">Rarity:</span>
                            </span>
                            <label class="filter-label">
                                <input type="checkbox" id="filter-rarity-normal" checked>
                                <span class="label-text">
                                    <span class="lang-ko">일반</span><span class="lang-en">Normal</span>
                                </span>
                            </label>
                            <label class="filter-label">
                                <input type="checkbox" id="filter-rarity-magic" checked>
                                <span class="label-text">
                                    <span class="lang-ko">마법</span><span class="lang-en">Magic</span>
                                </span>
                            </label>
                            <label class="filter-label">
                                <input type="checkbox" id="filter-rarity-rare" checked>
                                <span class="label-text">
                                    <span class="lang-ko">희귀</span><span class="lang-en">Rare</span>
                                </span>
                            </label>
                        </div>
                        <div class="filter-row tier-filter-row">
                            <span style="font-weight: bold; margin-right: 15px;">
                                <span class="lang-ko">타락:</span>
                                <span class="lang-en">Corrupted:</span>
                            </span>
                            <label class="filter-label">
                                <input type="checkbox" id="filter-corrupted-yes">
                                <span class="label-text">
                                    <span class="lang-ko">Yes</span><span class="lang-en">Yes</span>
                                </span>
                            </label>
                            <label class="filter-label">
                                <input type="checkbox" id="filter-corrupted-no">
                                <span class="label-text">
                                    <span class="lang-ko">No</span><span class="lang-en">No</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="mod-area">
            <div class="option-group">
                <h3><span class="lang-ko">❌제외할 모드</span><span class="lang-en">❌Mods to Exclude</span></h3>
                <div style="margin-bottom: 10px;">
                    <input type="text" id="searchExclude" class="mod-search" placeholder="Search...">
                </div>
                <div id="mod-list-bad" class="option-list">
                    </div>
            </div>
            <div class="option-group">
                <h3><span class="lang-ko">✅포함할 모드</span><span class="lang-en">✅Mods to Include</span></h3>
                <div style="margin-bottom: 10px;">
                    <input type="text" id="searchInclude" class="mod-search" placeholder="Search...">
                </div>
                <div id="mod-list-good" class="option-list">
                    </div>
            </div>
        </div>

    </div>
</div>

<template id="condition-row-template">
    <div class="condition-row">
        <select class="condition-type">
            <option value="quantity">아이템 수량</option>
            <option value="rarity">아이템 희귀도</option>
            <option value="packsize">몬스터 무리 규모</option>
            <option value="currency">화폐 더 많음</option>
            <option value="scarab">갑충석 더 많음</option>
            <option value="map">지도 더 많음</option>
        </select>
        <input type="number" class="condition-value" placeholder="Minimum (%)">
        <button class="delete-condition-button" title="이 조건 삭제">×</button>
    </div>
</template>