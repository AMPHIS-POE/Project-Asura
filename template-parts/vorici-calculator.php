<div id="vorici" class="section vorici-section">
  <div class="vorici-container">
    <div class="vorici-wrapper">
      <h1 class="vorici-title">
        <span data-lang="ko">보리치 색채 계산기</span>
        <span data-lang="en">Vorici Chromatic<br>Calculator</span>
      </h1>

      <div class="vorici-calculator">
        <div class="vorici-form">
          <label>
            <span data-lang="ko">총 소켓 수</span>
            <span data-lang="en">Total Sockets</span>
          </label>
          <input id="socket" type="number" value="6" class="vorici-input">

          <label>
            <span data-lang="ko">장비 요구 능력치</span>
            <span data-lang="en">Requirements</span>
          </label>
          <div class="vorici-input-group">
            <div class="vorici-input-wrapper">
              <label for="req_str">
                <span data-lang="ko">힘</span>
                <span data-lang="en">STR</span>
              </label>
              <input id="req_str" class="vorici-input vorici-red" type="number" value="0">
            </div>
            <div class="vorici-input-wrapper">
              <label for="req_dex">
                <span data-lang="ko">민첩</span>
                <span data-lang="en">DEX</span>
              </label>
              <input id="req_dex" class="vorici-input vorici-green" type="number" value="0">
            </div>
            <div class="vorici-input-wrapper">
              <label for="req_int">
                <span data-lang="ko">지능</span>
                <span data-lang="en">INT</span>
              </label>
              <input id="req_int" class="vorici-input vorici-blue" type="number" value="0">
            </div>
          </div>

          <label>
            <span data-lang="ko">원하는 홈 색깔</span>
            <span data-lang="en">Desired Colors</span>
          </label>
          <div class="vorici-input-group">
            <div class="vorici-input-wrapper">
              <label for="des_r">
                <span data-lang="ko">빨강</span>
                <span data-lang="en">Red</span>
              </label>
              <input id="des_r" class="vorici-input vorici-red" type="number" value="0">
            </div>
            <div class="vorici-input-wrapper">
              <label for="des_g">
                <span data-lang="ko">초록</span>
                <span data-lang="en">Green</span>
              </label>
              <input id="des_g" class="vorici-input vorici-green" type="number" value="0">
            </div>
            <div class="vorici-input-wrapper">
              <label for="des_b">
                <span data-lang="ko">파랑</span>
                <span data-lang="en">Blue</span>
              </label>
              <input id="des_b" class="vorici-input vorici-blue" type="number" value="0">
            </div>
          </div>
        </div>

        <div class="vorici-button-container">
          <button class="vorici-btn" onclick="calculate()">
            <span data-lang="ko">계산</span>
            <span data-lang="en">Calc</span>
          </button>
        </div>
      </div>

      <table class="vorici-table">
        <thead>
          <tr>
            <th><span data-lang="ko">제작 유형</span><span data-lang="en">Craft Type</span></th>
            <th><span data-lang="ko">평균 비용</span><span data-lang="en">Avg Cost</span></th>
            <th><span data-lang="ko">성공률</span><span data-lang="en">Success %</span></th>
            <th><span data-lang="ko">평균 시도</span><span data-lang="en">Avg Attempt</span></th>
          </tr>
        </thead>
        <tbody id="resultTable"></tbody>
      </table>
    </div>
  </div>
</div>