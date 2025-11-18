<?php
/**
 * Template pour les paramètres administrateur Framaspace
 *
 * @var array $_
 * @var array $apps Liste des applications installées
 */

script('framaspace', 'admin-settings');
style('framaspace', 'admin-settings');

// CSS inline pour le masquage des apps
if (!empty($_['apps'])) {
    $hiddenApps = array_filter($_['apps'], fn($app) => $app['hidden']);
    if (!empty($hiddenApps)) {
        echo '<style>';
        foreach ($hiddenApps as $app) {
            echo "#appmenu li[data-id=\"{$app['id']}\"] { display: none !important; }\n";
            echo "#app-navigation-vue li[data-id=\"{$app['id']}\"] { display: none !important; }\n";
            echo "a[href*=\"/apps/{$app['id']}/\"] { display: none !important; }\n";
        }
        echo "#appmenu { display: flex !important; }\n";
        echo "#appmenu li:not([style*=\"display: none\"]) { display: inline-block !important; }\n";
        echo '</style>';
    }
}
?>

<div id="framaspace-admin-settings">
    <div class="feature-description">
        <p><strong>Information :</strong> Vous pouvez personnaliser votre interface et celle des utilisateurs de votre espace en masquant certaines applications du menu. Les applications cochées comme "cachées" ne seront plus visibles dans le menu pour vous et pour tous les utilisateurs de ce Framaspace.</p>
        <p><strong>ATTENTION :</strong> Les applications "Fichiers" et "Activité" ne peuvent pas être masquées car elles sont essentielles au fonctionnement de Framaspace.</p>
    </div>
    
    <form id="hidden-apps-form">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>État</th>
                    <th>Cachée dans le menu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_['apps'] as $app): ?>
                <tr>
                    <td><?php p($app['name']); ?></td>
                    <td><?php p($app['enabled'] ? 'Activée' : 'Désactivée'); ?></td>
                    <td>
                        <?php if ($app['protected']): ?>
                            <input type="checkbox" 
                                   id="app-<?php p($app['id']); ?>" 
                                   disabled
                                   title="Cette application ne peut pas être masquée">
                            <label for="app-<?php p($app['id']); ?>" style="color: #999;">Protégée</label>
                        <?php else: ?>
                            <input type="checkbox" 
                                   id="app-<?php p($app['id']); ?>" 
                                   name="hidden_apps[]" 
                                   value="<?php p($app['id']); ?>"
                                   <?php if ($app['hidden']): ?>checked<?php endif; ?>>
                            <label for="app-<?php p($app['id']); ?>">Cacher</label>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="form-actions">
            <button type="submit" class="button primary">Sauvegarder</button>
            <span id="save-status" class="save-status"></span>
        </div>
    </form>
</div>