import { useEffect, useState } from 'react';
import axios from "axios";
import { BsFillStarFill, BsStar, BsPencilSquare, BsFillTrashFill} from "react-icons/bs";
import DeleteRating from "./DeleteRating";
import UpdateRating from "./UpdateRating";
import { UncontrolledTooltip, Table } from 'reactstrap';
import './Ratings.css';


function Ratings() {
    const [ratings, setRatings] = useState("");
    const username = sessionStorage.getItem('username');
    const [updateRating, setUpdateRating] = useState(null);
    const [deleteRating, setDeleteRating] = useState(null);



    const user = localStorage.getItem("user");

    
    useEffect(() => {
        axios.get("http://localhost/index.php/rating/get")
        .then((response) => {
            setRatings(response.data);
        })
        .catch(err => console.log(err));
    }, []);

    if (!ratings) return null;

    const isUserRating = (rating) => {
        return user === rating.username;
      };

      const clickUpdate = (rating) => {
        setUpdateRating(rating);
        setDeleteRating(null);
      };

      const clickDelete = (rating) => {
        setUpdateRating(null);
        setDeleteRating(rating);
      };

    const stars = (rating) => {
        const max = 5;
        const stars = [];

        for(let i=0; i<max; i++) {
            if (i<rating) {
                stars[i] = <BsFillStarFill /**style={{color:'yellow'}}*//>;
            }
            else {
                stars[i] = <BsStar/>;
            }
        }
        return <div>{stars}</div>;
    };

    return(
        <div className="mainContainer">
            <h1>Ratings</h1>
            <div className={"titleContainer"}>
                <Table>
                    {ratings.map((rating) => (
                        <div key={rating.id}>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        {rating.id}
                                    </th>
                                    <td>
                                        {rating.song}
                                    </td>
                                    <td className='artist'>
                                        by {rating.artist}
                                    </td>
                                    <td>
                                    @{rating.username}
                                    </td>
                                    <td>
                                        {stars(rating.rating)}
                                    </td>
                                    <td>
                                        {true && (
                                            <div style={{display:'inline-flex'}} >
                                                <div style={{marginRight:'20px'}}>
                                                    <BsPencilSquare id={`update-icon${rating.id}`} ratingId={rating.id} onClick={() => clickUpdate(rating.id)}/>
                                                    <UncontrolledTooltip target={`update-icon${rating.id}`} style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginBottom:'5px'}}>Edit<br/>your<br/>rating!</UncontrolledTooltip>
                                                </div>
                                                <div style={{marginRight:'5px'}}>
                                                    <BsFillTrashFill id={`delete-icon${rating.id}`} ratingId={rating.id} onClick={() => clickDelete(rating.id)}/>
                                                    <UncontrolledTooltip target={`delete-icon${rating.id}`} style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginBottom:'5px'}}>Delete<br/>your<br/>rating!</UncontrolledTooltip>
                                                </div>
                                            </div>
                                        )}
                                    </td>
                                </tr>
                            </tbody>
                            <div>
                                {(deleteRating===rating.id) && (
                                    <DeleteRating ratingId={deleteRating} onDelete={() => setDeleteRating(null)}/>
                                )}
                                {(updateRating===rating.id) && (
                                    <UpdateRating ratingId={updateRating} song={rating.song} artist={rating.artist} prevRating={rating.rating}/>
                                )}
                            </div>
                        </div>
                    ))}
                </Table>
            </div>
            <div>
            <h1>Hello, {username}</h1>
             </div>
        </div>
    );
}

export default Ratings