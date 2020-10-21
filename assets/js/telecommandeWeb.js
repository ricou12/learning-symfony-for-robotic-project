const blocText = () => {
    return `
        <p>Le serveur node JS déployé sur le microcontrôleur raspberry permet d'établir la communication entre
            l'utilisateur et le microcontrôleur arduino qui executera l'action demandée.</p>
        <p>Les commandes sélectionnées par l'utilisateur sont transmisent au serveur JS grace au protocole TCP
            (Transmission Control Protocol) en utilisant les sockets assurant une bonne communication (système
        d'accusés de réception).
        </p>
        <p>Le programme téléversé sur l'arduino récupère les données entrantes via le port série, puis vérifie avec
            une structure conditionnelles si la commande est reconnue. <br>Enfin le programme exécute l'action ou
            renvoie un message d'erreur via le port série, cette fois les données transmisent sont redirgées vers
            l'utilisateur.
        </p>
        <p><a href="https://github.com/ricou12/IHM-robotique" target="_blank"><br>
            Pour consulter le projet</a> rendez-vous sur Github.
        </p>
        <p>Ci-dessous le code source du server JS commenté.
        </p>`
}

const base_container_video_2 = () => {
    return `
    <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
        <img src="/build/images/telecom.png" alt="Telecommande-web" class="interfaceWeb__image">
        <button class="interfaceWeb__video__play-button">Voir la vidéo</button>
    </div>
    <div class="col-12 col-md-8 mt-4 mt-md-0 blocText">
        ${blocText()}
    </div>
    `
}

const playVideo = () => {
    return `<div class="col-12 d-flex justify-content-center align-items-start">
        <video class="videoWeb interfaceWeb__video--height" controls autoplay>
            <source src="/build/video/telecom.mp4" type="video/mp4">
                <p>Votre navigateur ne prend pas en charge les vidéos HTML5. Voici 
                    <a href="/build/video/telecom.mp4">Un lien pour télécharger la vidéo</a>.
                </p>
        </video>
        </div> 
        <div class="col-12 mt-4">
            ${blocText()}
        </div>`;
}

const container_video_2 = document.querySelector('.interfaceWeb__video');
container_video_2.innerHTML = base_container_video_2();

const rechargeVideo_2 = () => {
    document.querySelector(".interfaceWeb__video__play-button").addEventListener('click', () => {
        container_video_2.innerHTML = playVideo();
        document.querySelector('.videoWeb').addEventListener('ended', () => {
            container_video_2.innerHTML = base_container_video_2();
            rechargeVideo_2();
        });
    });
}

rechargeVideo_2();