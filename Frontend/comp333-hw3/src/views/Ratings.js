import { useEffect, useState } from 'react';
import axios from "axios";
import { BsFillStarFill, BsStar, BsPencilSquare, BsFillTrashFill} from "react-icons/bs";
import DeleteRating from "./DeleteRating";
import UpdateRating from "./UpdateRating";
import { UncontrolledTooltip, Table } from 'reactstrap';
import './Ratings.css';


function Ratings(props) {
    const [limit, setLimit] = useState(10);
    const [ratings, setRatings] = useState(null);
    const [updateRating, setUpdateRating] = useState(null);
    const [deleteRating, setDeleteRating] = useState(null);
    const [dataChange, setDataChange] = useState(false);
    const user = localStorage.getItem('username');
    const defaultLimit = 15;

    const handleChange = (event) => {
        const value = event.target.value;
        setLimit(value);
      };

    const handleDataChange = (props) => {
        setDataChange(!dataChange); 
        setUpdateRating(null);
      };
    
    useEffect(() => {
        axios.get(`http://localhost/index.php/rating/get?limit=${(localStorage.getItem('limit')) ?localStorage.getItem('limit') : defaultLimit}`)
        .then((response) => {
            setRatings(response.data);
        })
        .catch(err => console.log(err));
    }, [dataChange, props.dataChange]);

    if (!ratings) {
        return null;
    }

      const clickUpdate = (rating) => {
        setUpdateRating((updateRating) =>
        updateRating === rating ? null : rating
        );
        setDeleteRating(null);
      };

      const clickDelete = (rating) => {
            setDeleteRating((deleteRating) =>
                deleteRating === rating ? null : rating
            );
            setUpdateRating(null);
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
            {ratings && (<div className={"titleContainer"}>
            <form onSubmit={() => localStorage.setItem('limit', parseInt(limit))}>
                <label>Show first 
                <input 
                    type="number" 
                    name="limit" 
                    onChange={handleChange}
                    style={{marginBottom:'10px', marginTop:'10px', marginRight:'2px', marginLeft:'2px', width:'40px'}}
                />songs.
                </label>
                <input id='limit-submit' type="submit" style={{marginTop:"10px", marginLeft:'10px'}}/>
            </form>
            <p style={{color:'blue'}}> You are currently showing the first {localStorage.getItem('limit')} ratings in the system.</p>
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
                                        {(user === rating.username) && (
                                            <div style={{display:'inline-flex'}} >
                                                <div id='update-icon' style={{marginRight:'20px'}}>
                                                    <BsPencilSquare id={`update-icon${rating.id}`} ratingId={rating.id} onClick={() => clickUpdate(rating.id)}/>
                                                    <UncontrolledTooltip target={`update-icon${rating.id}`} style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginBottom:'5px'}}>Edit<br/>rating<br/>{rating.id}!</UncontrolledTooltip>
                                                </div>
                                                <div style={{marginRight:'5px'}}>
                                                    <BsFillTrashFill id={`delete-icon${rating.id}`} ratingId={rating.id} onClick={() => clickDelete(rating.id)}/>
                                                    <UncontrolledTooltip target={`delete-icon${rating.id}`} style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginBottom:'5px'}}>Delete<br/>rating<br/>{rating.id}!</UncontrolledTooltip>
                                                </div>
                                            </div>
                                        )}
                                    </td>
                                </tr>
                            </tbody>
                            <div>
                                {(user) && (deleteRating===rating.id) && (
                                    <DeleteRating ratingId={deleteRating} onDelete={() => clickDelete(rating)} onDataChange={handleDataChange}/>
                                )}
                                {(user) && (updateRating===rating.id) && (
                                    <UpdateRating ratingId={updateRating} song={rating.song} artist={rating.artist} prevRating={rating.rating} onDelete={() => clickUpdate(rating)} onDataChange={handleDataChange}/>
                                )}
                            </div>
                        </div>
                    ))}
                </Table>
            </div>
            )}
        </div>
    );
}

export default Ratings