import { createHashRouter } from "react-router-dom";
import App from "./components/App";
import List from "./components/List";
import AddPost from "./components/AddPost";
import UpdatePost from "./components/UpdatePost";


export const router = createHashRouter([
    {
        path:"/",
        element:<App/>,
        children:[
            {
                path:"/",
                element:<List/>
            },
            {
                path:"/create",
                element:<AddPost/>
            },
            {
                path:"/edit/:postId",
                element:<UpdatePost/>
            },
        ]
    }
])