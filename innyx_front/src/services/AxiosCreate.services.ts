import axios from "axios";

const baseURL = 'http://localhost:3000';


const apiin = axios.create({
    withCredentials: false,
    baseURL: `${baseURL}`,
    
  });
  
  export { apiin };
  