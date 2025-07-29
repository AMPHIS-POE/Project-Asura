<div id="map" class="section map-section active">
    <div class="container">
        <div class="map-overlay">
            <h1>
                <span data-lang="ko">17티어 지도 계산기</span>
                <span data-lang="en">17 Tier Map Calculator</span>
            </h1>
            <p>
                <span data-lang="ko">
                    모든 가격은 POE.NINJA의 값으로 자동 설정됩니다<br>
                    원하신다면 가격을 직접 수정하실 수 있습니다
                </span>
                <span data-lang="en">
                    All prices are automatically based on POE.NINJA<br>
                    If you wish, you can manually enter and adjust the prices
                </span>
            </p>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>
                                <span data-lang="ko">지도 이름</span>
                                <span data-lang="en">Map Name</span>
                            </th>
                            <th>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/img/Chaos.png'; ?>" class="currency-icon" alt="Chaos Orb Icon">
                                <span data-lang="ko">가격</span>
                                <span data-lang="en">Price</span>
                            </th>
                            <th>
                                <span data-lang="ko">수량</span>
                                <span data-lang="en">Quantity</span>
                            </th>
                            <th>
                                <span data-lang="ko">총합</span>
                                <span data-lang="en">Total</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="mapTable">
                        <tr>
                            <td>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/img/Sanctuary.png'; ?>" class="map-icon" alt="Sanctuary Icon">
                                <span class="map-label" data-lang="ko">성역</span>
                                <span class="map-label" data-lang="en">Sanctuary</span>
                            </td>
                            <td>
                                <input class="price" type="number" value="0" step="1" data-map="Sanctuary">
                            </td>
                            <td>
                                <input class="count" type="number" value="0" step="1">
                            </td>
                            <td class="total">0</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/img/Fortress.png'; ?>" class="map-icon" alt="Fortress Icon">
                                <span class="map-label" data-lang="ko">요새</span>
                                <span class="map-label" data-lang="en">Fortress</span>
                            </td>
                            <td>
                                <input class="price" type="number" value="0" step="1" data-map="Fortress">
                            </td>
                            <td>
                                <input class="count" type="number" value="0" step="1">
                            </td>
                            <td class="total">0</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/img/Citadel.png'; ?>" class="map-icon" alt="Citadel Icon">
                                <span class="map-label" data-lang="ko">성채</span>
                                <span class="map-label" data-lang="en">Citadel</span>
                            </td>
                            <td>
                                <input class="price" type="number" value="0" step="1" data-map="Citadel">
                            </td>
                            <td>
                                <input class="count" type="number" value="0" step="1">
                            </td>
                            <td class="total">0</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/img/Ziggurat.png'; ?>" class="map-icon" alt="Ziggurat Icon">
                                <span class="map-label" data-lang="ko">지구라트</span>
                                <span class="map-label" data-lang="en">Ziggurat</span>
                            </td>
                            <td>
                                <input class="price" type="number" value="0" step="1" data-map="Ziggurat">
                            </td>
                            <td>
                                <input class="count" type="number" value="0" step="1">
                            </td>
                            <td class="total">0</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/img/Abomination.png'; ?>" class="map-icon" alt="Abomination Icon">
                                <span class="map-label" data-lang="ko">흉물</span>
                                <span class="map-label" data-lang="en">Abomination</span>
                            </td>
                            <td>
                                <input class="price" type="number" value="0" step="1" data-map="Abomination">
                            </td>
                            <td>
                                <input class="count" type="number" value="0" step="1">
                            </td>
                            <td class="total">0</td>
                        </tr>
                    </tbody>
                </table>

                <div class="summary-row">
                    <div class="summary-box">
                        <p>
                            <img src="/wp-content/themes/asura-child/img/Divine.png" class="currency-icon"> 1 :
                            <img src="/wp-content/themes/asura-child/img/Chaos.png" class="currency-icon">
                            <input id="divineRate" type="number" placeholder="로딩중..." step="1">
                        </p>
                    </div>

                    <div class="summary-box center-box">
                        <button id="copySummary">
                            <span data-lang="ko">판매 문구 복사</span>
                            <span data-lang="en">Copy Summary</span>
                        </button>
                    </div>

                    <div class="summary-box" style="text-align: right;">
                        <h3>
                            <span data-lang="ko">총합</span>
                            <span data-lang="en">Total</span>
                        </h3>
                        <p>
                            <img src="/wp-content/themes/asura-child/img/Chaos.png" class="currency-icon">
                            <span data-lang="ko">카오스 오브 기준: </span>
                            <span data-lang="en">Total (Chaos): </span>
                            <span id="totalChaos" class="total-numbers">0</span>
                        </p>
                        <p>
                            <img src="/wp-content/themes/asura-child/img/Divine.png" class="currency-icon">
                            <span data-lang="ko">신성한 오브 기준: </span>
                            <span data-lang="en">Total (Divine): </span>
                            <span id="totalDivine" class="total-numbers">0</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> </div>