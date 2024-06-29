let modalWindow = document.querySelector("#modalWindow");
let buttonPrimary = document.querySelector(".btn-primary");

if(buttonPrimary) {
  buttonPrimary.click();
}

// Create a new MutationObserver 
const observer = new MutationObserver((mutations) => { 
    mutations.forEach((mutation) => { 
      console.log(mutation.type);  // mutation.type will be "attributes", "childList", or "characterData" 
      modalWindow.style.width="100vw";
      modalWindow.style.backdropFilter = "blur(8.7px)"; // un effet glassmorphisme en arrière-plan
    }); 
  }); 
   
  // Configure the observer to watch for changes 
  const config = { attributes: true, childList: true, subtree: true }; 
  observer.observe(modalWindow, config); 
   
  // Later, you can disconnect the observer when you no longer need it 
  //observer.disconnect(); 

  console.log("hello world");

  // si la fenetre change, ecouter le modal
  // Si c'est le modal qui est présent, alors mettre le width à 100vw
  // si le modal n'est pas p