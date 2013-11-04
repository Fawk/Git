<?php

/**
 * Message short summary.
 *
 * Message description.
 *
 * @version 1.0
 * @author Fawk
 */
class Message
{
    private $messages = array("Registering lyckades! Du kan nu logga in.",
                              "Användaren som angavs finns inte!",
                              "Information i kakor är felaktig!",
							  "Användarnamn saknas!",
							  "Lösenord saknas!",
							  "Lösenorden matchar inte!",
                              "Fel användarnamn eller lösenord!",
							  "Användarnamn är för kort, minst %s tecken!",
                              "Användarnamn är för långt, högst %s tecken!",
                              "Lösenord är för kort, minst %s tecken!",
                              "Lösenord är för långt, högst %s tecken!",
                              "Användarnamnet är upptaget!",
                              "Användaren %s har inga formulär!",
                              "Titel på formulär är för kort, minst %s tecken!",
                              "Titel på formulär är för långt, högst %s tecken!",
                              "Beskrivning för formulär är för kort, minst %s tecken!",
                              "Beskrivning för formulär är för långt, högst %s tecken!",
                              "Titel innehåller ogiltiga tecken!",
                              "Beskrivning innehåller ogiltiga tecken!",
                              "Formuläret som angavs finns inte!",
                              "Formuläret skapades utan problem.",
                              "Formuläret ändrades utan problem.",
                              "Formuläret togs bort utan problem.",
                              "Ditt svar har blivit registrerat, tack!",
                              "Användarnamnet innehåller ogiltiga tecken!");

	/**
     * Funktion som returnar meddelande åt vyn
     * 
     * 0  : Registering lyckades! Du kan nu logga in.
     * 1  : Användaren som angavs finns inte!
     * 2  : Information i kakor är felaktig!
     * 3  : Användarnamn saknas!
     * 4  : Lösenord saknas!
     * 5  : Lösenorden matchar inte!
     * 6  : Fel användarnamn eller lösenord!
     * 7  : Användarnamn är för kort, minst %s tecken!
     * 8  : Användarnamn är för långt, högst %s tecken!
     * 9  : Lösenord är för kort, minst %s tecken!
     * 10 : Lösenord är för långt, högst %s tecken!
     * 11 : Användarnamnet är upptaget!
     * 12 : Användaren %s har inga formulär!
     * 13 : Titel på formulär är för kort, minst %s tecken!
     * 14 : Titel på formulär är för långt, högst %s tecken!
     * 15 : Beskrivning för formulär är för kort, minst %s tecken!
     * 16 : Beskrivning för formulär är för långt, högst %s tecken!
     * 17 : Titel innehåller ogiltiga tecken!
     * 18 : Beskrivning innehåller ogiltiga tecken!
     * 19 : Formuläret som angavs finns inte!
     * 20 : Formuläret skapades utan problem.
     * 21 : Formuläret ändrades utan problem.
     * 22 : Formuläret togs bort utan problem.
     * 23 : Ditt svar har blivit registrerat, tack!
     * 24 : Användarnamnet innehåller ogiltiga tecken!
     */
	public function get($int)
	{
		return $this->messages[$int];
	}
}
