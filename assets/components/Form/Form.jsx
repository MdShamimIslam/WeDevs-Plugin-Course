import { useEffect, useState } from "react";

const Form = () => {
  const [formValues, setFormValues] = useState({});
  const {
    title,
    select_option,
    select_radio,
    select_checkbox = [],
  } = formValues || {};

  const { ajax_url, nonce } = reactSettings || {};

  const handleFormSubmit = (e) => {
    e.preventDefault();

    let formdata = new FormData(e.target);

    if (select_checkbox && select_checkbox.length > 0) {
      select_checkbox.forEach((item) => {
        formdata.append("select_checkbox[]", item);
      });
    } else {
      formdata.append("select_checkbox", "");
    }

    formdata.append("action", "react_form_submit");
    formdata.append("nonce", nonce);

    fetch(ajax_url, {
      method: "POST",
      body: formdata,
    });
  };

  useEffect(() => {
    const url = `${ajax_url}?action=react_get_form_data&nonce=${nonce}`;

    fetch(url)
      .then((res) => {
        return res.json();
      })
      .then((data) => {
        setFormValues({
          ...data?.data,
          select_checkbox: data?.data?.select_checkbox || [],
        });
      });
  }, []);

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;

    setFormValues((prevValues) => {
      if (type === "checkbox") {
        const updatedCheckboxes = checked
          ? [...(prevValues[name] || []), value]
          : (prevValues[name] || []).filter((item) => item !== value);

        return { ...prevValues, [name]: updatedCheckboxes };
      }

      return { ...prevValues, [name]: value };
    });
  };

  return (
    <div>
      <form onSubmit={handleFormSubmit}>
        <table className="form-table">
          <tbody>
            <tr>
              <th>
                <label>Title</label>
              </th>
              <td>
                <input
                  name="title"
                  type="text"
                  value={title || ""}
                  onChange={handleChange}
                />
              </td>
            </tr>
            <tr>
              <th>
                <label>Select Option</label>
              </th>
              <td>
                <select
                  name="select_option"
                  value={select_option || ""}
                  onChange={handleChange}
                >
                  <option value="1"> 1 </option>
                  <option value="2"> 2 </option>
                  <option value="3"> 3 </option>
                </select>
              </td>
            </tr>
            <tr>
              <th> Gender </th>
              <td>
                <fieldset>
                  <label>
                    <input
                      type="radio"
                      name="select_radio"
                      value="male"
                      checked={select_radio === "male"}
                      onChange={handleChange}
                    />
                    Male
                  </label>
                  <br />
                  <label>
                    <input
                      type="radio"
                      name="select_radio"
                      value="female"
                      checked={select_radio === "female"}
                      onChange={handleChange}
                    />
                    Female
                  </label>
                  <br />
                  <label>
                    <input
                      type="radio"
                      name="select_radio"
                      value="others"
                      checked={select_radio === "others"}
                      onChange={handleChange}
                    />
                    Others
                  </label>
                </fieldset>
              </td>
            </tr>
            <tr>
              <th> Choose Color </th>
              <td>
                <fieldset>
                  <label>
                    <input
                      type="checkbox"
                      name="select_checkbox"
                      value="Red"
                      checked={select_checkbox?.includes("Red")}
                      onChange={handleChange}
                    />
                    Red
                  </label>
                  <br />
                  <label>
                    <input
                      type="checkbox"
                      name="select_checkbox"
                      value="White"
                      checked={select_checkbox?.includes("White")}
                      onChange={handleChange}
                    />
                    White
                  </label>
                  <br />
                  <label>
                    <input
                      type="checkbox"
                      name="select_checkbox"
                      value="Green"
                      checked={select_checkbox?.includes("Green")}
                      onChange={handleChange}
                    />
                    Green
                  </label>
                </fieldset>
              </td>
            </tr>
          </tbody>
        </table>
        <p className="submit">
          <input
            type="submit"
            name="submit"
            id="submit"
            className="button button-primary"
            value="Save"
          />
        </p>
      </form>
    </div>
  );
};

export default Form;
