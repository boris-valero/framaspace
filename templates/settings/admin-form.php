<?php
/**
 * Template pour les paramètres administrateur Framaspace
 *
 * @var array $_
 * @var array $apps Liste des applications installées
 */

script('framaspace', 'admin-settings');

// CSS inline pour l'interface admin et le masquage des apps
echo '<style>';
echo '
#framaspace-admin-settings {
    max-width: 700px;
}

#framaspace-admin-settings .feature-description {
    background-color: #e3f2fd;
    border: 1px solid #2196f3;
    color: #0d47a1;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

#framaspace-admin-settings .feature-description p {
    margin: 0;
    line-height: 1.5;
}

#framaspace-admin-settings table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    table-layout: fixed;
}

#framaspace-admin-settings th,
#framaspace-admin-settings td {
    padding: 15px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

#framaspace-admin-settings th:nth-child(1),
#framaspace-admin-settings td:nth-child(1) {
    width: 60%;
}

#framaspace-admin-settings th:nth-child(2),
#framaspace-admin-settings td:nth-child(2) {
    width: 40%;
    text-align: center;
}

#framaspace-admin-settings th {
    background-color: #f5f5f5;
    font-weight: bold;
}

#framaspace-admin-settings .form-actions {
    margin: 20px 0;
}

#framaspace-admin-settings .save-status {
    margin-left: 10px;
    font-weight: bold;
}

#framaspace-admin-settings .save-status.success {
    color: #28a745;
}

#framaspace-admin-settings .save-status.error {
    color: #dc3545;
}

#framaspace-admin-settings .info-box {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #FFFF00;
    padding: 15px;
    border-radius: 4px;
    margin-top: 20px;
}

#framaspace-admin-settings input[type="checkbox"] {
    margin-right: 8px;
}

#framaspace-admin-settings label {
    cursor: pointer;
    user-select: none;
}
';

if (!empty($_['apps'])) {
	$hiddenApps = array_filter($_['apps'], fn ($app) => $app['hidden']);
	if (!empty($hiddenApps)) {
		foreach ($hiddenApps as $app) {
			echo "#appmenu li[data-id=\"{$app['id']}\"] { display: none !important; }\n";
			echo "#app-navigation-vue li[data-id=\"{$app['id']}\"] { display: none !important; }\n";
			echo "a[href*=\"/apps/{$app['id']}/\"] { display: none !important; }\n";
		}
		echo "#appmenu { display: flex !important; }\n";
		echo "#appmenu li:not([style*=\"display: none\"]) { display: inline-block !important; }\n";
	}
}
echo '</style>';
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