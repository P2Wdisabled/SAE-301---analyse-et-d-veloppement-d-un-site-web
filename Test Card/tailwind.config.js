const { hsl } = require('color-convert');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{html,js}"],
  theme: {
    extend: {
      backgroundColor:{
        "card-color": 'var(--card-color)',
        "bg-color": 'var(--bg-color)',
      },
      textColor:{
        "color-purple": 'var(--text-pruple)',
        "color-white": 'var(--text-white)',
        "color-gray": 'var(--text-gray)',
      },
      },
      filters: {
       saturate: 'saturate(100%)',
        invert: 'invert(34%)',
        sepia: 'sepia(5%)',
        saturate: 'saturate(6354%)',
        rotate: 'hue-rotate(255deg)',
        brightness: 'brightness(30%)',
        contrast: 'contrast(114%)',
      },
      width: {
        "card-size1": 'var(--card-size1)',
        "card-size2": 'var(--card-size2)',
        "card-size3": 'var(--card-size3)',
        "nav-size": 'var(--nav-size)',
       
      },
      height: {
       
        "card-height1": 'var(--card-height1)',
        "card-height2": 'var(--card-height2)',
        "card-height3": 'var(--card-height3)',
      },
     
    },
  
   

    
  };
  