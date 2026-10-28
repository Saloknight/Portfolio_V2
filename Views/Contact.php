<?php
sendmsg()
?>
<section class="contact">
    <div class="container">
        <h2 class="titre">Contactez-moi</h2>
        <p>N'hésitez pas à me contacter, je vous répondrai dans les plus brefs délais.</p>

        <form action="<?= homeUrl()?>Contact" method="POST" class="row">

            <div class="col-6">
                <label>Nom*</label>
                <input required name="nom" type="text" placeholder="Nom">
            </div>

            <div class="col-6">
                <label>Prénom*</label>
                <input required name="prenom" type="text" placeholder="Prenom">
            </div>

            <div class="col-6">
                <label>Mail*</label>
                <input required name="email" type="email" placeholder="votre-mail@gmail.com">
            </div>

            <div class="col-6">
                <label>Téléphone</label>
                <input name="telephone" type="tel" placeholder="06 06 06 06 06">
            </div>
            
            <div class="col-12">
                <label>Objet*</label>
                <input required name="objet" type="text">
            </div>

            <div class="col-12 ">
                <label>Message*</label>
                <textarea required name="message"></textarea>
            </div>

            <div class="col-12">* Champs obligatoire</div>

            <div class="check col-12">
                <input type="checkbox" id="consent" name="consent" required>
                <label for="consent">
                    J'accepte que mes données soient traitées pour répondre à ma demande. 
                    <a href="<?= homeUrl()?>Politique">En savoir plus</a>.
                </label>
            </div>

            <div class="col-12 submit">
                <button type="submit" class="send">Envoyer</button>
            </div>
        </form>

        




    </div>
</section>