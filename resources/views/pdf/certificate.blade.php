<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; text-align: center; padding: 50px; border: 20px solid #4F46E5; }
        .title { font-size: 50px; color: #4F46E5; margin-bottom: 20px; }
        .subtitle { font-size: 20px; color: #6B7280; }
        .name { font-size: 40px; font-weight: bold; margin: 30px 0; border-bottom: 2px solid #EEE; display: inline-block; }
        .course { font-size: 25px; color: #1F2937; font-style: italic; }
        .footer { margin-top: 50px; font-size: 15px; color: #9CA3AF; }
    </style>
</head>
<body>
    <div class="subtitle">SERTIFIKAT PENGHARGAAN</div>
    <div class="title">ZeroCourse LMS</div>
    <p>Diberikan kepada:</p>
    <div class="name">{{ $name }}</div>
    <p>Atas keberhasilannya menyelesaikan kursus:</p>
    <div class="course">"{{ $course }}"</div>
    <div class="footer">Diterbitkan pada {{ $date }} | ID Sertifikat: {{ uniqid() }}</div>
</body>
</html>