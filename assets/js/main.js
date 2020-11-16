const base_container_video = () => {
    return `
    <div class="o-container robotique__video__container">
        <button class="robotique__video__play-button">Regarder la vidéo</button>              
    </div>`;
}

const play_video = () => {
    return `
    <video class="robotique__video--height videoWeb-1" controls autoplay>
        <source src="build/medias/video/video.mp4" type="video/mp4">
            <p>Votre navigateur ne prend pas en charge les vidéos HTML5. Voici 
                <a href="/build/medias/video/video.mp4">un lien pour télécharger la vidéo</a>.
            </p>
    </video>`;
}

const container_video = document.querySelector('.robotique__video');
container_video.innerHTML = base_container_video();

const rechargeVideo_1 = () => {
    document.querySelector(".robotique__video__play-button").addEventListener('click', () => {
        container_video.innerHTML = play_video();
        document.querySelector('.videoWeb-1').addEventListener('ended', () => {
            container_video.innerHTML = base_container_video();
            rechargeVideo_1();
        });
    });
}

rechargeVideo_1();