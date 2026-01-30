<!-- app/views/home/index.php -->






<?php
// Sample data for development/demo purposes
if (!isset($sections)) {
    $sections = [
        [
            'name' => 'General',
            'forums' => [
                [
                    'id' => 1,
                    'name' => 'Announcements',
                    'description' => 'Latest news and updates.',
                    'topics_count' => 12,
                    'replies_count' => 58,
                    'last_post' => [
                        'thread_title' => 'Forum Rules Updated',
                        'thread_id' => 101,
                        'user' => 'Admin',
                        'user_id' => 1,
                        'created_at' => '2024-06-01 14:23'
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Introductions',
                    'description' => 'Say hello to the community!',
                    'topics_count' => 34,
                    'replies_count' => 120,
                    'last_post' => [
                        'thread_title' => 'Welcome, new members!',
                        'thread_id' => 102,
                        'user' => 'JaneDoe',
                        'user_id' => 5,
                        'created_at' => '2024-06-02 09:10'
                    ]
                ]
            ]
        ],
        [
            'name' => 'Support',
            'forums' => [
                [
                    'id' => 3,
                    'name' => 'Help Desk',
                    'description' => 'Get help with your issues.',
                    'topics_count' => 8,
                    'replies_count' => 27,
                    'last_post' => [
                        'thread_title' => 'Login problems',
                        'thread_id' => 103,
                        'user' => 'SupportGuy',
                        'user_id' => 3,
                        'created_at' => '2024-06-03 16:45'
                    ]
                ]
            ]
        ],
        [
            'name' => 'Off Topic',
            'forums' => [
                [
                    'id' => 4,
                    'name' => 'Chit Chat',
                    'description' => 'Talk about anything!',
                    'topics_count' => 15,
                    'replies_count' => 60,
                    'last_post' => [
                        'thread_title' => 'Favorite movies?',
                        'thread_id' => 104,
                        'user' => 'MovieBuff',
                        'user_id' => 7,
                        'created_at' => '2024-06-04 11:30'
                    ]
                ]
            ]
        ]
    ];
}
?>


<div class="header-menu">
    <p>
        <?= t('current_lang_label') ?><br>
        <?= t('welcome') ?><br>
        <?= t('login') ?><br>
        <?= t('posts') ?><br>
    </p>
</div>

<a href="/en" style="margin-left: 16px;">English</a>
<a href="/it" style="margin-left: 8px;">Italiano</a>
<a href="/dashboard" style="margin-left: 8px;">Dashboard</a>

</div>



<div class="sections-list">
    <?php if (!empty($sections)): ?>
        <?php foreach ($sections as $idx => $section): ?>
            <div class="section" id="section-<?= $idx ?>">
                <h2 style="cursor:pointer; display: flex; align-items: center;" onclick="toggleSection(<?= $idx ?>)">
                    <span class="toggle-icon" id="toggle-icon-<?= $idx ?>" style="margin-right:8px; display:inline-block; vertical-align:middle;">
                        <!-- Plus SVG (shown when closed), Minus SVG (shown when open) -->
                        <svg id="icon-plus-<?= $idx ?>" width="18" height="18" viewBox="0 0 18 18" fill="none" style="vertical-align:middle;"><rect x="7" y="3" width="4" height="12" rx="2" fill="#888"/><rect x="3" y="7" width="12" height="4" rx="2" fill="#888"/></svg>
                        <svg id="icon-minus-<?= $idx ?>" width="18" height="18" viewBox="0 0 18 18" fill="none" style="vertical-align:middle; display:none;"><rect x="3" y="7" width="12" height="4" rx="2" fill="#888"/></svg>
                    </span>
                    <?= htmlspecialchars($section['name']) ?>
                </h2>
                <div class="section-content" id="section-content-<?= $idx ?>">
                    <?php if (!empty($section['forums'])): ?>
                        <ul class="forums-list">
                            <?php foreach ($section['forums'] as $forum): ?>
                                <li>
                                    <a href="/forum/view/<?= (int)$forum['id'] ?>">
                                        <?= htmlspecialchars($forum['name']) ?>
                                    </a>
                                    <?php if (!empty($forum['description'])): ?>
                                        <div class="forum-desc"><?= htmlspecialchars($forum['description']) ?></div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No forums in this section yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No sections found.</p>
    <?php endif; ?>
</div>
<script>
function toggleSection(idx) {
    var content = document.getElementById('section-content-' + idx);
    var plus = document.getElementById('icon-plus-' + idx);
    var minus = document.getElementById('icon-minus-' + idx);

    if (content.style.display === 'none') {
        // Open section: show content, show minus (close), hide plus (open)
        content.style.display = '';
        plus.style.display = 'none';
        minus.style.display = '';
    } else {
        // Close section: hide content, show plus (open), hide minus (close)
        content.style.display = 'none';
        plus.style.display = '';
        minus.style.display = 'none';
    }
}
function initSections() {
    <?php foreach ($sections as $idx => $section): ?>
    // Ensure all sections start open (default)
    document.getElementById('section-content-<?= $idx ?>').style.display = '';
    document.getElementById('icon-plus-<?= $idx ?>').style.display = 'none';
    document.getElementById('icon-minus-<?= $idx ?>').style.display = '';
    <?php endforeach; ?>
}

document.addEventListener('DOMContentLoaded', initSections);
// Optional: Start with all sections open, or closed by default
// To start closed, uncomment below:
// document.addEventListener('DOMContentLoaded', function() {
//     <?php foreach ($sections as $idx => $section): ?>
//     toggleSection(<?= $idx ?>);
//     <?php endforeach; ?>
// });
</script>
