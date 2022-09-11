import axios from "axios";

export default class {
    static async fetchGroups() {
        const response = await axios.get('/api/groups');
        if (response.data.success === false) {
            throw new Error(response.data.text);
        }
        return response.data.data.groups;
    }
}
