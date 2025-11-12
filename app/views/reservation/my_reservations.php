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
            <th>Acțiuni</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $r): ?>
            <tr>
                <td><?php echo (int)$r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['title']); ?></td>
                <td><?php echo htmlspecialchars($r['start_time']); ?></td>
                <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                <td>
                    <form method="post"
                          action="/?controller=reservation&action=delete"
                          style="display:inline;"
                          onsubmit="return confirm('Sigur vrei să ștergi această rezervare?');">
                        <input type="hidden" name="csrf_token"
                               value="<?php echo CSRFTokenService::generateToken(); ?>">
                        <input type="hidden" name="reservation_id"
                               value="<?php echo (int)$r['id']; ?>">
                        <button type="submit" class="btn-link-danger">Șterge</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
