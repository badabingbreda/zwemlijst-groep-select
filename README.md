# Zwemlijst groep select plugin

Deze plugin moet er voor zorgen dat je meerdere leerlingen tegelijk kunt toevoegen, namelijk alle leerlingen die in een groep zitten, gedefinieerd door de taxonomy 'groep'.

<img src="/docs/example.png" alt="Met groep toevoegen"/>

Voeg een veld toe boven je multirelationship veldtype. Aangenomen wordt dat de naam/ID van dat veld 'multirelation' is.

			array(
			    'name' => 'Voeg leerlingen toe uit groep',
			    'type' => 'custom_html',
			    // HTML content
			    'std'  => '<select class="group_add"></select><input type="button" value="Voeg ze toe" class="group_add_action" data-students=""><style>.group_add_action[data-students=""] { display:none; }</style>',
			),
#### LET OP
De plugin is niet 'flexibel', maar gaat uit van benamingen van onder andere het veld waarin het de leerlingen toevoegt, de naam van de taxonomie en de slug van de term waarmee de gestopte leerlingen worden aangemerkt.
Iemand met verstand van wat coderen moet deze plugin wel weer werkend kunnen maken indien je iets in de genoemde afhankelijkheden verandert.