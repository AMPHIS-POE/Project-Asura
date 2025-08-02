<footer class="custom-site-footer">
    <div class="footer-container">
        <div class="footer-disclaimer">
            <p>developed by AMPHIS and JAMES</p>
        </div>
        <div class="footer-social-icons">
            <a href="https://www.youtube.com/@amphis8954" target="_blank" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
            <a href="https://discord.gg/invite/7FBPtyhjBx" target="_blank" title="Discord"><i class="fa-brands fa-discord"></i></a>
            <a href="https://www.instagram.com/heinejjw" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
            <a href="#contact-popup" title="Email"><i class="fa-solid fa-envelope"></i></a>
        </div>
        <div class="footer-copyright">
            <p>© <?php echo date('Y'); ?> Asura</p>
        </div>
    </div>
</footer>
<a id="custom-back-to-top" title="Scroll back to top">▲</a>

<div id="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<div id="contact-popup" class="contact-popup-overlay">
    <div class="contact-popup-content">
        <h2>
            <span data-lang="ko">메시지 보내기</span>
            <span data-lang="en">Send a Message</span>
        </h2>
        <p class="contact-popup-subtitle">
            <span data-lang="ko">궁금한 점이나 제안할 내용이 있으시면<br>언제든지 메시지를 보내주세요.</span>
            <span data-lang="en">If you have any questions or suggestions,<br>feel free to send a message.</span>
        </p>
        <a href="#" class="close-popup" title-ko="닫기" title-en="Close">×</a>
        <form action="https://formspree.io/f/xldllnlj" method="POST">
            <label for="contact-name"><span data-lang="ko">이름</span><span data-lang="en">Name</span></label>
            <input id="contact-name" type="text" name="name" required>
            <label for="contact-email"><span data-lang="ko">답변받을 이메일</span><span data-lang="en">Your Email</span></label>
            <input id="contact-email" type="email" name="email" required>
            <label for="contact-message"><span data-lang="ko">내용</span><span data-lang="en">Message</span></label>
            <textarea id="contact-message" name="message" rows="6" required></textarea>
            <button type="submit"><span data-lang="ko">보내기</span><span data-lang="en">Send</span></button>
        </form>
    </div>
</div>