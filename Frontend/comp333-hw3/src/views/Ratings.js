import React from "react"
import { useNavigate } from "react-router-dom";

const Ratings = (props) => {
    const { loggedIn, username } = props
    const navigate = useNavigate();
    
    const onButtonClick = () => {
        // You'll update this function later
    }

    return <div className="mainContainer">
        <div className={"titleContainer"}>
            <div>Welcome!</div>
        </div>
        <div>
            This is the ratings page.
        </div>
        <div className={"buttonContainer"}>
            <input
                className={"inputButton"}
                type="button"
                onClick={onButtonClick}
                value={loggedIn ? "Log out" : "Log in"} />
            {(loggedIn ? <div>
                Your username is {username}
            </div> : <div/>)}
        </div>


    </div>
}

export default Ratings