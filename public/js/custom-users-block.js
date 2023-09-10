import SelectUserField from './SelectUserField';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('custom-users-block/block', {
    title: __('Custom Users Block', 'custom-users-block-plugin'), // Translate the block title
    icon: 'shield',
    category: 'common',
    attributes: {
        selectedUsers: {
            type: 'array',
            default: [],
        },
    },
    edit: function (props) {
        const { attributes, setAttributes } = props;
        const { selectedUsers } = attributes;

        // Handle adding/removing selected users
        const handleSelectUser = (newUser) => {
            const updatedUsers = [...selectedUsers, newUser];
            setAttributes({ selectedUsers: updatedUsers });
        };

        // Handle removing a user by ID
        const handleRemoveUser = (userId) => {
            const updatedUsers = selectedUsers.filter((user) => user !== userId);
            setAttributes({ selectedUsers: updatedUsers });
        };

        return (
            <div>
                {selectedUsers.map((userId, index) => (
                    <div key={userId}>
                        <SelectUserField
                            selectedUser={userId}
                            onSelect={handleSelectUser}
                            label={`Select User ${index + 1}`}
                        />
                        <button onClick={() => handleRemoveUser(userId)}>
                            Remove User
                        </button>
                    </div>
                ))}
            </div>
        );
    },
    save: function () {
        return null;
    },
});
