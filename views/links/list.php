

<div class="uk-section uk-animation-fade">
    <h3 class="uk-margin-small-bottom">Danh sách link</h3>
    <div class="uk-overflow-auto">
        <table class="uk-table uk-table-middle uk-table-divider" style="max-height: 500px; overflow-y: auto">
            <thead>
                <tr>
                    <th class="uk-width-small">Tài khoản</th>
                    <th class="uk-table-expand">Link đích</th>
                    <th class="uk-width-medium">Rút gọn</th>
                    <th class="uk-width-small">Thời gian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( count( $links ) > 0 ) { ?>
                    <?php foreach($links as $link) { 
                        $user = User::get_user_by_id($link['user_id']);
                        $date = date_create($link['created_at']);
                        ?>
                        <tr>
                            <td class="uk-text-nowrap"><?= $user['username']; ?></td>
                            <td class="uk-text-truncate"><?= $link['long_url']; ?></td>
                            <td class="uk-text-truncate"><?= SITE_URL . '/?u=' . $link['short_url']; ?></td>
                            <td class="uk-text-nowrap"><?= date_format($date,"d-m-Y H:i:s"); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="uk-width-small">Tài khoản</th>
                    <th class="uk-table-expand">Link đích</th>
                    <th class="uk-width-medium">Rút gọn</th>
                    <th class="uk-width-small">Thời gian</th>
                </tr>
            </tfoot>
        </table>
        <?php
        $pagination = Link::pagination();
        echo $pagination;
        ?>
    </div>
</div>