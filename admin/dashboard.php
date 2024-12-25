<div class="wrap precipice">

    <div class="link-area">
        <ul>
            <li><li class="fa-brands fa-google"> </li> <a href="#">Google Analytics</a></li>
        </ul>
    </div>
    <h1 class="wp-heading-inline"><? _e( 'Dashboard', 'precipice' ); ?></h1>
 
    <select name="dashboard-filter" id="dashboard-filter">
        <option value="year" selected><?php _e( 'This Week', 'precipice' ); ?></option>
        <option value="year"><?php _e( 'This Month', 'precipice' ); ?></option>
        <option value="year"><?php _e( 'This Year', 'precipice' ); ?></option>
    </select>
    <label for="dashboard-filter"><?php _e( 'Date range', 'precipice' ); ?></label>


    <div class='tile-area'>
        <?php foreach( $this->getSortedTiles() as $tile ) { ?>
            <div class="tile<?php if ( $tile->big ) echo " large";?>" id="tile-<?php esc_attr_e( $tile->slug ); ?>">
                <div class="trend"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <h2><?php esc_html_e( $tile->name ); ?></h2> 
               
                <div class="big"><?php esc_html_e( $tile->big_text ); ?></div>

                <div class="wrapper">
                     <canvas class="chart"></canvas>
                </div>

                <?php if ( $tile->list_headings ) { ?>
                    <table class="tile-table">
                        <tr>
                        <?php foreach( $tile->list_headings as $heading ) { ?>
                            <th><?php esc_html_e( $heading ); ?></th>
                        <?php } ?>
                        </tr>
                        <?php foreach( $tile->list_data as $oneLine ) { ?>
                        <tr class="data">
                            <?php foreach( $oneLine as $oneItem ) { ?>
                                <td><?php esc_html_e( $oneItem ); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>