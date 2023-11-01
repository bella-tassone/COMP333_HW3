import { Routes, Route } from "react-router-dom";
import Ratings from "./views/Ratings";
import Login from "./views/Login";
import Registration from "./views/Registration";
import AddRating from "./views/AddRating";
import { useEffect, useState } from 'react';
import { useNavigate, useLocation } from "react-router-dom";
import './App.css';
import { UncontrolledTooltip } from 'reactstrap';




function App() {
  const [loggedIn, setLoggedIn] = useState(false);
  const [dataChange, setDataChange] = useState(false);
  const navigate = useNavigate();
  const location = useLocation();


  useEffect(() => {
    const loggedInUser = localStorage.getItem("username");
    if (loggedInUser) {
      setLoggedIn(true);
    }
  });

  const logout = () => {
    localStorage.removeItem("username");
    setLoggedIn(false);
  }

  const refreshRatingsData = () => {
    setDataChange(!dataChange);
  };

  return (
      <div style={{display:'inline-flex',marginLeft:"20px"}}>
        <Routes>
            <Route path="/" element={<Ratings dataChange={dataChange} />} />
            <Route path="login" element={<Login />} />
            <Route path="registration" element={<Registration />} />
        </Routes>
        {loggedIn && (
          <div style={{marginLeft:"100px"}} >
          <AddRating onRatingAdded={refreshRatingsData} />
          </div>
        )}
          <div>
            {(!loggedIn && location.pathname === '/') && (
              <div>
                <button className="login-button" id='login' onClick={() => navigate("login")}>
                  Login
                </button>
                <UncontrolledTooltip target='login' placement='left' style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginRight:'5px', marginTop:'5px'}}>Login to your<br/>account here!</UncontrolledTooltip>
              </div>
            )}
            {loggedIn && (
              <div>
                <button className="logout-button" id='logout' onClick={() => logout()}>
                  Log Out
                </button>
                <UncontrolledTooltip target='logout' placement='left' style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginRight:'5px', marginTop:'5px'}}>Log out of your<br/>account here!</UncontrolledTooltip>
              </div>
            )}
          </div>
      </div>
  );
}

export default App;
