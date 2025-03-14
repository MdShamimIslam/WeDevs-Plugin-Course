import { useState } from "react";
import { useNavigate } from "react-router-dom";

function AddPost() {
  const [isButtonDisabled, setButtonDisabled] = useState(false);
  const [buttonText, setButtonText] = useState("Save");
  const navigate = useNavigate();

  const onFormSubmit = (event) => {
    event.preventDefault();

    setButtonDisabled(true);
    setButtonText("Saving");

    let formValues = new FormData(event.target);

    formValues.append("action", "cd_save_values");
    formValues.append("nonce", ReactSettings.nonce);

    fetch(ReactSettings.ajax_url, {
      method: "POST",
      body: formValues,
    })
      .then((response) => response.json())
      .then((response) => {
        if (response.success) {
          // Redirect to list.
          navigate("/");
        }
      });  

};

  return (
    <div>
      <form onSubmit={onFormSubmit}>
        <table className="form-table">
          <tbody>
            <tr>
              <th>
                <label>Title</label>
              </th>
              <td>
                <input
                  type="text"
                  className="regular-text widefat"
                  name="title"
                />
              </td>
            </tr>
            <tr>
              <th>
                <label>Description</label>
              </th>
              <td>
                <textarea
                  name="description"
                  className="large-text code"
                ></textarea>
              </td>
            </tr>
          </tbody>
        </table>
        <p>
          <input
            type="submit"
            className="button button-primary"
            value={buttonText}
            disabled={isButtonDisabled}
          />
        </p>
      </form>
    </div>
  );
}

export default AddPost;
