

<div class="uk-section uk-animation-fade">
    <h3 class="uk-margin-small-bottom">Danh sách thành viên</h3>
    <div class="uk-overflow-auto">
        <table class="uk-table uk-table-middle uk-table-divider" style="max-height: 500px; overflow-y: auto">
            <thead>
                <tr>
                    <th class="uk-table-shrink">ID</th>
                    <th class="uk-width-medium">Tên</th>
                    <th class="uk-width-medium">Username</th>
                    <th class="uk-width-medium">Email</th>
                    <th class="uk-width-small">Vai trò</th>
                    <th class="uk-width-small">Thời gian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( count( $users ) > 0 ) { ?>
                    <?php foreach($users as $user) {
                        $date = date_create($user['created_at']);
                        ?>
                        <tr>
                            <td class="uk-text-nowrap"><?= $user['id']; ?></td>
                            <td class="uk-text-nowrap"></td>
                            <td class="uk-text-nowrap"><?= $user['username']; ?></td>
                            <td class="uk-text-truncate"><?= $user['email']; ?></td>
                            <td class="uk-text-nowrap"><?= $user['role'] == 1 ? 'Admin' : 'Thành viên' ; ?></td>
                            <td class="uk-text-nowrap"><?= date_format($date,"d-m-Y H:i:s"); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="uk-table-shrink">ID</th>
                    <th class="uk-width-medium">Tên</th>
                    <th class="uk-width-medium">Username</th>
                    <th class="uk-width-medium">Email</th>
                    <th class="uk-width-small">Vai trò</th>
                    <th class="uk-width-small">Thời gian</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
    $pagination = User::pagination();
    echo $pagination;
    ?>
</div>