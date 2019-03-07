
# WPshop 2

## Guide d'installation
- Installer Dolibarr
- Activer les **modules** de Dolibarr :
	-  Tiers
	- Propositions commerciales
	- Commandes client
	-  Factures et avoirs
	- Produits
	- Services
	- Stock
	- API/Web Services (serveur REST)
	- Paypal
- Dans l'onglet **Super Admin**, générer une clé API
- Dans l'onglet **Configuration** -> **Divers**, ajouter une ligne avec, comme données :
	- Nom : PRODUCT_PRICE_UNIQ
	- Valeur : 1
- Installer votre WordPress
- Installer et activer l'extension WPshop v2.x.x
- Dans les options de WPshop, Ajouter la clé API précédemment générée dans l'onglet "Dolibarr Secret"
