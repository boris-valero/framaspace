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
        <p><strong>Information :</strong> Personnalisez l'interface en masquant certaines applications du menu (hors Fichiers et Activité) pour vous et pour tous les utilisateurs de ce Framaspace. </p> <br /> 
        <p><strong>Mode d'emploi :</strong> Cochez la case de la colonne de droite pour masquer une application et décochez cette même case pour l'afficher de nouveau. N'oubliez pas de cliquer sur "Sauvegarder". Au prochain rechargement de la page, l'icône sera masquée. </p> <br />
    </div>
    
    <form id="hidden-apps-form">
        <table>
            <thead>
                <tr>
                    <th>Nom de l'application</th>
                    <th>Souhaitez-vous cacher l'icône ?</th>
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