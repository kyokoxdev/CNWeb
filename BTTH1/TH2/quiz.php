<?php
session_start();

function LoadFile($filename = 'Quiz.txt') {
    $questions = [];
    $content = file_get_contents($filename);
    
    $currentQuestion = null;
    $currentOptions = [];
    $currentAnswer = null;
    $questionId = 0;
    
    $lines = explode("\n", $content);
    $i = 0;
    
    while ($i < count($lines)) {
        $line = trim($lines[$i]);
        
        if (empty($line)) {
            $i++;
            continue;
        }
        
        if (strpos($line, 'ANSWER:') === 0) {
            $answerPart = trim(str_replace('ANSWER:', '', $line));
            $currentAnswer = array_map('trim', explode(',', $answerPart));
            
            if ($currentQuestion !== null) {
                $questionId++;
                $questions[] = [
                    'id' => $questionId,
                    'question' => $currentQuestion,
                    'options' => $currentOptions,
                    'answer' => $currentAnswer,
                    'multiple' => count($currentAnswer) > 1
                ];
            }
            
            $currentQuestion = null;
            $currentOptions = [];
            $currentAnswer = null;
            $i++;
            continue;
        }
        
        if (preg_match('/^([A-E])\.\s*(.+)$/', $line, $matches)) {
            $currentOptions[$matches[1]] = $matches[2];
            $i++;
            continue;
        }
        
        if ($currentQuestion === null) {
            $currentQuestion = $line;
        } else {
            $currentQuestion .= ' ' . $line;
        }
        
        $i++;
    }
    
    return $questions;
}

function getRandomQuestions($questions, $count = 10) {
    $shuffled = $questions;
    shuffle($shuffled);
    return array_slice($shuffled, 0, min($count, count($shuffled)));
}

function calculateScore($questions, $userAnswers) {
    $score = 0;
    $total = count($questions);
    $results = [];
    
    foreach ($questions as $q) {
        $qId = $q['id'];
        $userAnswer = isset($userAnswers[$qId]) ? $userAnswers[$qId] : [];
        
        if (!is_array($userAnswer)) {
            $userAnswer = [$userAnswer];
        }
        
        $correctAnswer = $q['answer'];
        sort($correctAnswer);
        sort($userAnswer);
        
        $isCorrect = ($correctAnswer == $userAnswer);
        
        if ($isCorrect) {
            $score++;
        }
        
        $results[] = [
            'question' => $q['question'],
            'options' => $q['options'],
            'userAnswer' => $userAnswer,
            'correctAnswer' => $q['answer'],
            'isCorrect' => $isCorrect,
            'multiple' => $q['multiple']
        ];
    }
    
    return [
        'score' => $score,
        'total' => $total,
        'percentage' => $total > 0 ? round(($score / $total) * 100, 1) : 0,
        'results' => $results
    ];
}

$page = 'quiz';
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {
    $page = 'result';
    $questions = $_SESSION['quiz_questions'] ?? [];
    $userAnswers = $_POST['answers'];
    $result = calculateScore($questions, $userAnswers);
    
    $timeTaken = time() - ($_SESSION['quiz_start_time'] ?? time());
    $minutes = floor($timeTaken / 60);
    $seconds = $timeTaken % 60;

    $percentage = $result['percentage'];
    if ($percentage >= 90) {
        $grade = 'A'; $gradeColor = '#27ae60'; $message = 'Xuất sắc! Bạn nắm vững kiến thức!';
    } elseif ($percentage >= 80) {
        $grade = 'B'; $gradeColor = '#2ecc71'; $message = 'Giỏi! Bạn có kiến thức tốt!';
    } elseif ($percentage >= 70) {
        $grade = 'C'; $gradeColor = '#f39c12'; $message = 'Khá! Cần ôn tập thêm một số chủ đề!';
    } elseif ($percentage >= 60) {
        $grade = 'D'; $gradeColor = '#e67e22'; $message = 'Trung bình! Hãy học thêm!';
    } else {
        $grade = 'F'; $gradeColor = '#e74c3c'; $message = 'Cần cố gắng hơn! Hãy ôn tập lại!';
    }
}

if ($page === 'quiz') {
    $allQuestions = LoadFile('Quiz.txt');
    $questionsPerQuiz = 20;
    
    if (!isset($_SESSION['quiz_questions']) || isset($_GET['new'])) {
        $_SESSION['quiz_questions'] = getRandomQuestions($allQuestions, $questionsPerQuiz);
        $_SESSION['quiz_start_time'] = time();
    }
    
    $questions = $_SESSION['quiz_questions'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page === 'quiz' ? 'Bài Kiểm Tra Trắc Nghiệm' : 'Kết Quả Bài Kiểm Tra'; ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container { max-width: 900px; margin: 0 auto; }
        
        .header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 25px;
            text-align: center;
        }
        
        .header h1 { color: #2c3e50; margin-bottom: 10px; font-size: 1.8em; }
        .header p { color: #7f8c8d; font-size: 1em; }
        
        .quiz-info {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .quiz-info span {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            color: #555;
        }
        
        .question-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .question-card:hover { transform: translateY(-3px); }
        
        .question-header {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .question-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .question-text { color: #2c3e50; font-size: 1.1em; line-height: 1.6; flex: 1; }
        
        .multiple-badge {
            background: #e74c3c;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.75em;
            margin-left: 10px;
            white-space: nowrap;
        }
        
        .options {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-left: 55px;
        }
        
        .option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .option:hover { border-color: #667eea; background: #f8f9ff; }
        .option.selected { border-color: #667eea; background: #eef2ff; }
        
        .option input[type="radio"],
        .option input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
        }
        
        .option-letter {
            background: #f0f0f0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #555;
        }
        
        .option.selected .option-letter { background: #667eea; color: white; }
        .option-text { flex: 1; color: #333; }
        
        .submit-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border: none;
            padding: 15px 50px;
            font-size: 1.2em;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.5);
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 1em;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-secondary { background: #95a5a6; color: white; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        
        .new-quiz-btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 20px;
            margin-left: 15px;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .new-quiz-btn:hover { transform: translateY(-2px); }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            margin-top: 15px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s;
        }
        
        .answered-count { text-align: center; margin-top: 10px; color: #7f8c8d; font-size: 0.9em; }
        
        /* Result Page Styles */
        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .score-circle .grade { font-size: 3em; font-weight: bold; }
        .score-circle .percentage { font-size: 1.2em; }
        
        .score-details {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .score-detail {
            background: #f8f9fa;
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
        }
        
        .score-detail .label { color: #7f8c8d; font-size: 0.9em; }
        .score-detail .value { color: #2c3e50; font-size: 1.5em; font-weight: bold; }
        
        .message { font-size: 1.2em; margin-bottom: 20px; }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .review-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .review-section h2 { color: #2c3e50; margin-bottom: 20px; text-align: center; }
        
        .review-question {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 5px solid;
        }
        
        .review-question.correct { background: #e8f5e9; border-color: #27ae60; }
        .review-question.incorrect { background: #ffebee; border-color: #e74c3c; }
        
        .review-question-text {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .review-question-text .status-icon { font-size: 1.2em; }
        .review-options { margin-left: 30px; }
        
        .review-option {
            padding: 8px 12px;
            margin-bottom: 5px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .review-option.user-selected { background: #fff3cd; border: 1px solid #ffc107; }
        .review-option.correct-answer { background: #d4edda; border: 1px solid #28a745; }
        .review-option.user-selected.correct-answer { background: #d4edda; border: 2px solid #28a745; }
        .review-option.user-selected.wrong { background: #f8d7da; border: 1px solid #dc3545; }
        
        .option-indicator {
            font-size: 0.8em;
            padding: 2px 8px;
            border-radius: 10px;
            color: white;
        }
        
        .indicator-correct { background: #28a745; }
        .indicator-wrong { background: #dc3545; }
        .indicator-missed { background: #6c757d; }
        
        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 20px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .filter-btn.active,
        .filter-btn:hover { background: #667eea; color: white; }
        
        @media (max-width: 600px) {
            .options { margin-left: 0; }
            .question-header { flex-direction: column; }
            .quiz-info { flex-direction: column; gap: 10px; }
            .score-details { flex-direction: column; gap: 10px; }
            .action-buttons { flex-direction: column; }
            .btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($page === 'quiz'): ?>
        <!-- QUIZ PAGE -->
        <div class="header">
            <h1>📱 Bài Kiểm Tra Trắc Nghiệm</h1>
            <p>Trả lời các câu hỏi sau đây để kiểm tra kiến thức của bạn</p>
            <div class="quiz-info">
                <span>📝 Số câu hỏi: <?php echo count($questions); ?></span>
                <span>⏱️ Thời gian: Không giới hạn</span>
                <span>✅ Chú ý: Một số câu có nhiều đáp án đúng</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="answered-count" id="answeredCount">Đã trả lời: 0/<?php echo count($questions); ?></div>
            <a href="quiz.php?new=1" class="new-quiz-btn">🔄 Làm bài mới</a>
        </div>
        
        <form action="quiz.php" method="POST" id="quizForm">
            <?php foreach ($questions as $index => $q): ?>
                <div class="question-card" id="question-<?php echo $q['id']; ?>">
                    <div class="question-header">
                        <div class="question-number"><?php echo $index + 1; ?></div>
                        <div class="question-text">
                            <?php echo htmlspecialchars($q['question']); ?>
                            <?php if ($q['multiple']): ?>
                                <span class="multiple-badge">Chọn <?php echo count($q['answer']); ?> đáp án</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="options">
                        <?php foreach ($q['options'] as $letter => $text): ?>
                            <label class="option" onclick="updateProgress()">
                                <?php if ($q['multiple']): ?>
                                    <input type="checkbox" 
                                           name="answers[<?php echo $q['id']; ?>][]" 
                                           value="<?php echo $letter; ?>">
                                <?php else: ?>
                                    <input type="radio" 
                                           name="answers[<?php echo $q['id']; ?>]" 
                                           value="<?php echo $letter; ?>">
                                <?php endif; ?>
                                <span class="option-letter"><?php echo $letter; ?></span>
                                <span class="option-text"><?php echo htmlspecialchars($text); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="submit-section">
                <button type="submit" class="submit-btn">📊 Nộp Bài & Xem Kết Quả</button>
            </div>
        </form>
        
        <script>
            document.querySelectorAll('.option').forEach(option => {
                option.addEventListener('click', function() {
                    const input = this.querySelector('input');
                    const parent = this.closest('.options');
                    
                    if (input.type === 'radio') {
                        parent.querySelectorAll('.option').forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');
                    } else {
                        this.classList.toggle('selected');
                    }
                    
                    updateProgress();
                });
            });
            
            function updateProgress() {
                setTimeout(() => {
                    const totalQuestions = <?php echo count($questions); ?>;
                    let answeredQuestions = 0;
                    
                    document.querySelectorAll('.question-card').forEach(card => {
                        const inputs = card.querySelectorAll('input:checked');
                        if (inputs.length > 0) answeredQuestions++;
                    });
                    
                    const percentage = (answeredQuestions / totalQuestions) * 100;
                    document.getElementById('progressFill').style.width = percentage + '%';
                    document.getElementById('answeredCount').textContent = 
                        'Đã trả lời: ' + answeredQuestions + '/' + totalQuestions;
                }, 100);
            }
            
            document.getElementById('quizForm').addEventListener('submit', function(e) {
                const totalQuestions = <?php echo count($questions); ?>;
                let answeredQuestions = 0;
                
                document.querySelectorAll('.question-card').forEach(card => {
                    const inputs = card.querySelectorAll('input:checked');
                    if (inputs.length > 0) answeredQuestions++;
                });
                
                if (answeredQuestions < totalQuestions) {
                    const confirm = window.confirm(
                        'Bạn chưa trả lời hết ' + (totalQuestions - answeredQuestions) + ' câu hỏi.\n' +
                        'Bạn có chắc muốn nộp bài không?'
                    );
                    if (!confirm) e.preventDefault();
                }
            });
        </script>
        
        <?php else: ?>
        <!-- RESULT PAGE -->
        <div class="header">
            <h1>📊 Kết Quả Bài Kiểm Tra</h1>
            
            <div class="score-circle" style="background: <?php echo $gradeColor; ?>;">
                <span class="grade"><?php echo $grade; ?></span>
                <span class="percentage"><?php echo $result['percentage']; ?>%</span>
            </div>
            
            <div class="message" style="color: <?php echo $gradeColor; ?>;"><?php echo $message; ?></div>
            
            <div class="score-details">
                <div class="score-detail">
                    <div class="label">Số câu đúng</div>
                    <div class="value" style="color: #27ae60;"><?php echo $result['score']; ?></div>
                </div>
                <div class="score-detail">
                    <div class="label">Số câu sai</div>
                    <div class="value" style="color: #e74c3c;"><?php echo $result['total'] - $result['score']; ?></div>
                </div>
                <div class="score-detail">
                    <div class="label">Tổng số câu</div>
                    <div class="value"><?php echo $result['total']; ?></div>
                </div>
                <div class="score-detail">
                    <div class="label">Thời gian</div>
                    <div class="value"><?php echo $minutes; ?>:<?php echo str_pad($seconds, 2, '0', STR_PAD_LEFT); ?></div>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="quiz.php?new=1" class="btn btn-primary">🔄 Làm Bài Mới</a>
                <a href="#review" class="btn btn-secondary" onclick="document.getElementById('reviewSection').scrollIntoView({behavior:'smooth'})">📝 Xem Chi Tiết</a>
            </div>
        </div>
        
        <div class="review-section" id="reviewSection">
            <h2>📝 Chi Tiết Bài Làm</h2>
            
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterQuestions('all', this)">Tất cả (<?php echo $result['total']; ?>)</button>
                <button class="filter-btn" onclick="filterQuestions('correct', this)">✅ Đúng (<?php echo $result['score']; ?>)</button>
                <button class="filter-btn" onclick="filterQuestions('incorrect', this)">❌ Sai (<?php echo $result['total'] - $result['score']; ?>)</button>
            </div>
            
            <?php foreach ($result['results'] as $index => $r): ?>
                <div class="review-question <?php echo $r['isCorrect'] ? 'correct' : 'incorrect'; ?>" 
                     data-status="<?php echo $r['isCorrect'] ? 'correct' : 'incorrect'; ?>">
                    <div class="review-question-text">
                        <span class="status-icon"><?php echo $r['isCorrect'] ? '✅' : '❌'; ?></span>
                        <span>Câu <?php echo $index + 1; ?>: <?php echo htmlspecialchars($r['question']); ?>
                            <?php if ($r['multiple']): ?>
                                <span style="background: #6c757d; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8em;">Nhiều đáp án</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="review-options">
                        <?php foreach ($r['options'] as $letter => $text): 
                            $isUserSelected = in_array($letter, $r['userAnswer']);
                            $isCorrectAnswer = in_array($letter, $r['correctAnswer']);
                            
                            $classes = 'review-option';
                            if ($isUserSelected && $isCorrectAnswer) {
                                $classes .= ' user-selected correct-answer';
                            } elseif ($isUserSelected && !$isCorrectAnswer) {
                                $classes .= ' user-selected wrong';
                            } elseif ($isCorrectAnswer) {
                                $classes .= ' correct-answer';
                            }
                        ?>
                            <div class="<?php echo $classes; ?>">
                                <strong><?php echo $letter; ?>.</strong>
                                <?php echo htmlspecialchars($text); ?>
                                <?php if ($isUserSelected && $isCorrectAnswer): ?>
                                    <span class="option-indicator indicator-correct">✓ Đúng</span>
                                <?php elseif ($isUserSelected && !$isCorrectAnswer): ?>
                                    <span class="option-indicator indicator-wrong">✗ Sai</span>
                                <?php elseif ($isCorrectAnswer): ?>
                                    <span class="option-indicator indicator-missed">Đáp án đúng</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <script>
            function filterQuestions(filter, btn) {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                document.querySelectorAll('.review-question').forEach(q => {
                    const status = q.dataset.status;
                    q.style.display = (filter === 'all' || status === filter) ? 'block' : 'none';
                });
            }
        </script>
        <?php endif; ?>
    </div>
</body>
</html>