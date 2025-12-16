<?php
/**
 * Template pour les paramètres administrateur Framaspace
 *
 * @var array $_
 * @var array $apps Liste des applications installées
 */

script('framaspace', 'admin-settings');
style('framaspace', 'admin-form');
?>

<div id="framaspace-admin-settings">
    <div class="feature-description">
        <p><strong><?php p($l->t('Information:')); ?></strong> <?php p($l->t('Customize the interface by hiding certain applications from the menu (except Files and Activity) for you and for all users of this Framaspace.')); ?> </p> <br /> 
        <p><strong><?php p($l->t('Instructions:')); ?></strong> <?php p($l->t('Check the box in the right column to hide an application and uncheck this same box to display it again. Don\'t forget to click "Save". On the next page reload, the icon will be hidden.')); ?> </p> <br />
    </div>
    
    <form id="hidden-apps-form">
        <table>
            <thead>
                <tr>
                    <th><?php p($l->t('Application name')); ?></th>
                    <th><?php p($l->t('Do you want to hide the icon?')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_['apps'] as $app): ?>
                <tr>
                    <td><?php p($app['name']); ?></td>
                    <td>
                        <?php if ($app['protected']): ?>
                            <input type="checkbox" 
                                   id="app-<?php p($app['id']); ?>" 
                                   disabled
                                   title="<?php p($l->t('This application cannot be hidden')); ?>">
                            <label for="app-<?php p($app['id']); ?>" style="color: #999;"><?php p($l->t('Protected')); ?></label>
                        <?php else: ?>
                            <input type="checkbox" 
                                   id="app-<?php p($app['id']); ?>" 
                                   name="hidden_apps[]" 
                                   value="<?php p($app['id']); ?>"
                                   <?php if ($app['hidden']): ?>checked<?php endif; ?>>
                            <label for="app-<?php p($app['id']); ?>"><?php p($l->t('Hide')); ?></label>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="form-actions">
            <button type="submit" class="button primary"><?php p($l->t('Save')); ?></button>
            <span id="save-status" class="save-status"></span>
        </div>
    </form>
</div>