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
          <Routes>
              <Route path="/" element={<Ratings username={username} loggedIn={loggedIn} setLoggedIn={setLoggedIn}/>} />
              <Route path="deleterating" element={<DeleteRating />} />
              <Route path="updaterating" element={<UpdateRating />} />
              <Route path="login" element={<Login setLoggedIn={setLoggedIn} setUsername={setUsername} />} />
              <Route path="registration" element={<Registration />} />
              <Route path="addrating" element={<AddRating />} />
              <Route path="viewrating" element={<ViewRating />} />
              <Route path="*" element={<NoPage />} />
          </Routes>
        </BrowserRouter>
  );
}

export default App;
