const { SelectControl } = wp.components;
const { useState, useEffect } = wp.element;

const SelectUserField = ({ selectedUsers, onSelect, label }) => {
    const [userOptions, setUserOptions] = useState([]);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        // Fetch users from the custom endpoint
        fetch('/wp-json/custom/v1/users')
            .then((response) => response.json())
            .then((data) => {
                // Map the user data to options for the select field
                const options = data.map((user) => ({
                    label: user.name,
                    value: user.id.toString(),
                }));

                setUserOptions(options);
                setIsLoading(false);
            })
            .catch((error) => {
                console.error('Error fetching users:', error);
            });
    }, []); // Empty dependency array ensures this effect runs only once

    // Handle user selection
    const handleUserChange = (selectedOptions) => {
        const selectedUserIds = selectedOptions.map((option) => option.value);
        onSelect(selectedUserIds);
    };

    // Create options from selected user IDs
    const selectedUserOptions = selectedUsers.map((userId) => ({
        label: userId,
        value: userId,
    }));

    return (
        <div>
            {/* Display loading indicator while fetching users */}
            {isLoading ? (
                <p>Loading users...</p>
            ) : (
                <SelectControl
                    label={label || 'Select Users'}
                    multiple
                    value={selectedUserOptions}
                    options={userOptions}
                    onChange={handleUserChange}
                />
            )}
        </div>
    );
};

export default SelectUserField;
