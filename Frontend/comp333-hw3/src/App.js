import { BrowserRouter, Routes, Route } from "react-router-dom";
import Ratings from "./views/Ratings";
import DeleteRating from "./views/DeleteRating";
import UpdateRating from "./views/UpdateRating";
import Login from "./views/Login";
import Registration from "./views/Registration";
import NoPage from "./views/NoPage";
import AddRating from "./views/AddRating";
import ViewRating from "./views/ViewRating";
import { useEffect, useState } from 'react';


function App() {
  const [loggedIn, setLoggedIn] = useState(false);
  const [username, setUsername] = useState("");

  return (
    <BrowserRouter>
        <div style={{display:'flex', marginLeft:"20px"}}>
          <Routes>
              <Route path="/" element={<Ratings />} />
              <Route path="login" element={<Login setLoggedIn={setLoggedIn} setUsername={setUsername} />} />
              <Route path="registration" element={<Registration />} />
          </Routes>
          <div style={{marginLeft:"100px"}} >
            <AddRating />
          </div>
        </div>
    </BrowserRouter>
  );
}

export default App;
