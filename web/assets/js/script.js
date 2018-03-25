function cacheImageAfficheMap(image) {
    image.style.display = "none";
    document.getElementById("map").style.display = "block";

    console.log(image)
    console.log(document.getElementById("map"))


}


function cacheMapAfficheImage(image) {
    image.style.display = "block";
    document.getElementById("map").style.display = "none";

    console.log(image)
    console.log(document.getElementById("map"))

}