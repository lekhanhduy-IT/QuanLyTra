<div class="container">
</div>
<style>
 body, html {
        overflow: hidden; /* Ngăn không cho cuộn dọc và cuộn ngang */
        height: 100%; /* Đảm bảo chiều cao 100% cho body và html */
        margin: 0; /* Bỏ margin mặc định */
    }

    .container {
        background-image: url('uploads/thum6.png');
        background-position: center; /* Giữ vị trí trung tâm của hình nền */
        background-attachment: fixed; /* Giữ cố định khi cuộn */
        background-size: cover;
        height: 100%;
        padding-top: 50px;
        margin: 0;
        border-top-right-radius: 10px; /* Bo góc phải */
        border-bottom-right-radius: 10px; /* Bo góc phải */
        padding-right: 20px;
        min-height: calc(80vh - 60px); /* Chiều cao tối thiểu trừ đi khoảng cách 60px từ đáy màn hình */
        margin-bottom: 60px; /* Khoảng cách 60px phía dưới */
    }
</style>