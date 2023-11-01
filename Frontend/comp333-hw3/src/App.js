import { Router, Routes, Route } from "react-router-dom";
import Ratings from "./views/Ratings";
import DeleteRating from "./views/DeleteRating";
import UpdateRating from "./views/UpdateRating";
import Login from "./views/Login";
import Registration from "./views/Registration";
import NoPage from "./views/NoPage";
import AddRating from "./views/AddRating";
import ViewRating from "./views/ViewRating";
import { useEffect, useState } from 'react';
import { useNavigate, useLocation } from "react-router-dom";
import './App.css';



function App() {
  const [loggedIn, setLoggedIn] = useState(false);
  const [username, setUsername] = useState("");
  const [dataChange, setDataChange] = useState(false);
  const navigate = useNavigate();
  const location = useLocation();


  useEffect(() => {
    const loggedInUser = localStorage.getItem("username");
    if (loggedInUser) {
      setUsername(loggedInUser);
      setLoggedIn(true);
    }
  });

  const logout = () => {
    localStorage.removeItem("username");
    setUsername("");
    setLoggedIn(false);
  }

  const refreshRatingsData = () => {
    setDataChange(!dataChange);
  };

  return (
      <div style={{display:'inline-flex',marginLeft:"20px"}}>
        <Routes>
            <Route path="/" element={<Ratings DataChanged={dataChange} />} />
            <Route path="login" element={<Login />} />
            <Route path="registration" element={<Registration />} />
        </Routes>
        {loggedIn && (
          <div style={{marginLeft:"100px"}} >
          <AddRating onRatingAdded={refreshRatingsData} />
          </div>
        )}
          <div style={{position:'relative'}}>
            {(!loggedIn && location.pathname === '/') && (
              <button className="login-button" onClick={() => navigate("login")}>
                Login
              </button>
            )}
            {loggedIn && (
              <button className="logout-button" onClick={() => logout()}>
                Log Out
              </button>
            )}
          </div>
      </div>
  );
}

export default App;
