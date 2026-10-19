/**
 * Permissions grouped by the `resource` prefix of their name.
 */
export type PermissionGroup = {
    resource: string;
    permissions: App.Data.PermissionData[];
};
