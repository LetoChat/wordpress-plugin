<div class="container-fluid h-100">
    <div class="row h-100">
        <div class="col-md-8">
            <div class="wrap">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <img style="max-height: 60px;" src="<?php echo PLUGIN_LETO_CHAT_URL ?>images/logo.gif" class="img-fluid" alt=""/>
                        </div>
                        <div class="col-md-7 text-right">
                            <h6><?php echo __('Do you encounter difficulties?', 'letochat'); ?> <a href=""><?php echo __('We can help you', 'letochat'); ?></a></h6>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="status" class="alert <?php echo empty($view['is_connected']) ? 'alert-warning' : ''; ?>" <?php echo empty($view['is_connected']) ? '' : 'style="display: none;"'; ?> role="alert">
                                <?php echo empty($view['is_connected']) ? __('To make the chat functional, please fill in the fields below!', 'letochat') : ''; ?>
                            </div>
                            <form id="leto-chat-data-form" method="POST" novalidate="novalidate">
                                <div class="form-group row align-items-center">
                                    <label for="channel-id" class="col-sm-3 col-form-label"><?php echo __('Channel ID', 'letochat'); ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="channel-id" name="channel_id" placeholder="Please insert channel ID" value="<?php echo $view['channel_id']; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="channel-secret" class="col-sm-3 col-form-label"><?php echo __('Channel Secret', 'letochat'); ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="channel-secret" name="channel_secret" placeholder="Please insert channel secret" value="<?php echo $view['channel_secret']; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="auth-secret" class="col-sm-3 col-form-label"><?php echo __('Auth Secret', 'letochat'); ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="auth-secret" name="auth_secret" placeholder="Please insert authentication secret" value="<?php echo $view['auth_secret']; ?>" required>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-5 text-right">
                                        <button type="button" id="paste-data" class="btn btn-info w-50" style="display: none;"><?php echo __('Paste data', 'letochat'); ?></button>
                                    </div>
                                    <div class="col-md-7">
                                        <button type="submit" id="save-leto-chat-data" class="btn btn-success mr-2 w-100"><span class="dashicons dashicons-yes"></span> <?php echo __('Save & Check', 'letochat'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr id="before-settings-switches"/>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td>
                                        <span class="label"><?php echo __('Enable Widget', 'letochat'); ?></span>
                                    </td>
                                    <td>
                                        <label class="toggle-switchy" for="enable-widget" data-size="lg">
                                            <input <?php echo $view['enable_widget'] === 'on' ? 'checked' : ''; ?> type="checkbox" id="enable-widget" name="enable_widget" value="true">
                                            <span class="toggle">
                                                <span class="switch"></span>
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Visible for admins', 'letochat'); ?></td>
                                    <td>
                                        <label class="toggle-switchy" for="visible-for-admins" data-size="lg">
                                            <input <?php echo $view['visible_for_admins'] === 'on' ? 'checked' : ''; ?> type="checkbox" id="visible-for-admins" name="visible_for_admins" value="true">
                                            <span class="toggle">
                                                <span class="switch"></span>
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 h-100" style="background-color: #f2f4f6;">
            <div class="wrap">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/hLfZXb_Ug6o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>