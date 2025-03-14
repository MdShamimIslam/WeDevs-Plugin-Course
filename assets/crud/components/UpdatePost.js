
import { useState, useEffect } from 'react';
import { useNavigate, useParams } from "react-router-dom";

function UpdatePost() {
    const [ isButtonDisabled, setButtonDisabled ] = useState(false);
    const [ postData, setPostData ] = useState({});
    const [ buttonText, setButtonText ] = useState('Save');
    const navigate = useNavigate();
    const { postId } = useParams();
    const {ajax_url, nonce} = ReactSettings || {};

    useEffect(() => {
        const url = `${ajax_url}?action=cd_get_item&id=${postId}&nonce=${nonce}`;

        fetch( url, {
            method: "GET",
        } ).then((response) => {
            return response.json();
        }).then((response) => {
            setPostData( response.data );
        });
    }, []);

    const onFormSubmit = (event) => {
        event.preventDefault();

        setButtonDisabled(true);
        setButtonText('Saving');

        let formValues = new FormData( event.target );

        formValues.append( 'action', 'cd_update_values' );
        formValues.append( 'nonce', nonce );
        formValues.append( 'id', postId );

        fetch( ajax_url, {
            method: "POST",
            body: formValues
        } ).then(response => response.json()).then((response) => {
            if ( response.success ) {
                // Redirect to list.
                navigate('/');
            }
        });
    };

    return (
        <form onSubmit={onFormSubmit}>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th>
                            <label>Title</label>
                        </th>
                        <td>
                            <input type="text" className="regular-text widefat" name="title" defaultValue={postData?.title} />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label>Description</label>
                        </th>
                        <td>
                            <textarea name="description" className="large-text code" defaultValue={postData?.description}></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>
                <input type="submit" className="button button-primary" value={buttonText} disabled={isButtonDisabled} />
            </p>
        </form>
    );
}

export default UpdatePost;
