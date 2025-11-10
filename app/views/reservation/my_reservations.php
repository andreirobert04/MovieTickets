<h2>Rezervările mele</h2>

<?php if (empty($reservations)): ?>
    <p>Nu ai încă nicio rezervare.</p>
<?php else: ?>
    <table class="showtimes-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Film</th>
            <th>Data & ora</th>
            <th>Creat la</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $r): ?>
            <tr>
                <td><?php echo (int)$r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['title']); ?></td>
                <td><?php echo htmlspecialchars($r['start_time']); ?></td>
                <td><?php echo htmlspecialchars($r['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
