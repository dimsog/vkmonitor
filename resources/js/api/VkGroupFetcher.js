import axios from "axios";
import VkGroupIdExtractor from "../helpers/VkGroupIdExtractor";

export default class {
    static async fetch(vkGroupLink) {
        const vkGroupId = VkGroupIdExtractor.extract(vkGroupLink);
        const response = await axios.get('/api/group/read/' + vkGroupId);
        if (response.data.success === false) {
            throw new Error(response.data.text);
        }
        return response.data.data.group;
    }
}
