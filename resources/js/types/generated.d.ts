declare namespace App {
namespace Data {
export type ContactData = {
name: string,
email: string,
subject: string | null,
message: string,
};
export type PermissionData = {
id: number,
name: string,
is_system: boolean,
};
export type PermissionFormData = {
name: string,
};
export type PostData = {
id: number,
name: string,
slug: string,
excerpt: string | null,
content?: string,
thumbnail: string | null,
published_at: string | null,
created_at: string | null,
updated_at: string | null,
deleted_at: string | null,
tags?: App.Data.TagData[],
};
export type PostFormData = {
name: string,
excerpt: string,
content: string,
thumbnail: File | string | null,
is_new_thumbnail: boolean,
published: boolean,
tags: string[] | null,
};
export type RoleData = {
id: number,
name: string,
label: string,
users_count: number,
is_protected: boolean,
permissions: string[],
};
export type RoleFormData = {
name: string,
permissions: string[],
};
export type TagData = {
id: number,
name: string,
slug: string,
};
export type TestFormData = {
text: string | null,
textarea: string | null,
taglistbox: (string | number)[] | null,
tags: string[] | null,
switch: boolean,
select: string | number | null,
radio: string | number | null,
phone: string | null,
password: string | null,
number: number | null,
markdown: string | null,
image: File | string | null,
is_new_image: boolean,
file: File | string | null,
combobox: string | number | null,
checkbox: boolean,
date: string | null,
datetime: string | null,
};
export type UserData = {
id: number,
name: string,
email: string,
role: string | null,
is_locked: boolean,
email_verified_at: string | null,
created_at: string | null,
updated_at: string | null,
};
export type UserFormData = {
name: string,
email: string,
role: string | null,
password: string | null,
password_confirmation: string | null,
};
}
}
declare namespace Illuminate {
export type CursorPaginator<TKey, TValue> = {
data: TKey extends string ? Record<TKey, TValue> : TValue[],
links: {
url: string | null,
label: string,
active: boolean,
}[],
meta: {
path: string,
per_page: number,
next_cursor: string | null,
next_page_url: string | null,
prev_cursor: string | null,
prev_page_url: string | null,
},
};
export type CursorPaginatorInterface<TKey, TValue> = Illuminate.CursorPaginator<TKey, TValue>;
export type LengthAwarePaginator<TKey, TValue> = {
data: TKey extends string ? Record<TKey, TValue> : TValue[],
links: {
url: string | null,
label: string,
active: boolean,
}[],
meta: {
total: number,
current_page: number,
first_page_url: string,
from: number | null,
last_page: number,
last_page_url: string,
next_page_url: string | null,
path: string,
per_page: number,
prev_page_url: string | null,
to: number | null,
},
};
export type LengthAwarePaginatorInterface<TKey, TValue> = Illuminate.LengthAwarePaginator<TKey, TValue>;
}
declare namespace Spatie {
namespace LaravelData {
export type CursorPaginatedDataCollection<TKey, TValue> = Illuminate.CursorPaginator<TKey, TValue>;
export type PaginatedDataCollection<TKey, TValue> = Illuminate.LengthAwarePaginator<TKey, TValue>;
}
}
