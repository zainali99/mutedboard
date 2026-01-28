<section>
    <h1>Your Profile</h1>
    <p>Welcome, <?= htmlspecialchars($user->username) ?>!</p>
    
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($user->id) ?></td>
                <td><?= htmlspecialchars($user->email) ?></td>
                <td><?= htmlspecialchars($user->role) ?></td>
            </tr>
        </tbody>
    </table>




</section>