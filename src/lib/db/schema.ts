import {
  pgTable,
  text,
  timestamp,
  integer,
  numeric,
  boolean,
  uuid,
  primaryKey,
} from "drizzle-orm/pg-core"

export const users = pgTable("users", {
  id: uuid("id").defaultRandom().primaryKey(),
  name: text("name"),
  email: text("email").unique(),
  emailVerified: timestamp("email_verified", { mode: "date" }),
  passwordHash: text("password_hash"),
  image: text("image"),
  role: text("role").default("member"), // "member" | "admin"
  createdAt: timestamp("created_at").defaultNow(),
})

export const accounts = pgTable("accounts", {
  id: uuid("id").defaultRandom().primaryKey(),
  userId: uuid("user_id").notNull().references(() => users.id),
  type: text("type").notNull(),
  provider: text("provider").notNull(),
  providerAccountId: text("provider_account_id").notNull(),
  refresh_token: text("refresh_token"),
  access_token: text("access_token"),
  expires_at: integer("expires_at"),
  token_type: text("token_type"),
  scope: text("scope"),
  id_token: text("id_token"),
  session_state: text("session_state"),
})

export const sessions = pgTable("sessions", {
  id: uuid("id").defaultRandom().primaryKey(),
  sessionToken: text("session_token").notNull().unique(),
  userId: uuid("user_id").notNull().references(() => users.id),
  expires: timestamp("expires").notNull(),
})

export const verificationTokens = pgTable(
  "verification_tokens",
  {
    identifier: text("identifier").notNull(),
    token: text("token").notNull(),
    expires: timestamp("expires").notNull(),
  },
  (vt) => ({
    compositePk: primaryKey({ columns: [vt.identifier, vt.token] }),
  })
)

export const categories = pgTable("categories", {
  id: uuid("id").defaultRandom().primaryKey(),
  name: text("name").notNull(),
  slug: text("slug").notNull().unique(),
})

export const books = pgTable("books", {
  id: uuid("id").defaultRandom().primaryKey(),
  title: text("title").notNull(),
  author: text("author").notNull(),
  isbn: text("isbn"),
  description: text("description"),
  totalCopies: integer("total_copies").default(1),
  availableCopies: integer("available_copies").default(1),
  categoryId: uuid("category_id").references(() => categories.id),
  fileUrl: text("file_url"),
  coverUrl: text("cover_url"),
  createdAt: timestamp("created_at").defaultNow(),
})

export const borrowings = pgTable("borrowings", {
  id: uuid("id").defaultRandom().primaryKey(),
  userId: uuid("user_id").notNull().references(() => users.id),
  bookId: uuid("book_id").notNull().references(() => books.id),
  borrowedAt: timestamp("borrowed_at").defaultNow(),
  dueAt: timestamp("due_at").notNull(),
  returnedAt: timestamp("returned_at"),
  extended: boolean("extended").default(false),
  fineAmount: numeric("fine_amount", { precision: 10, scale: 2 }).default("0"),
})

export const reservations = pgTable("reservations", {
  id: uuid("id").defaultRandom().primaryKey(),
  userId: uuid("user_id").notNull().references(() => users.id),
  bookId: uuid("book_id").notNull().references(() => books.id),
  createdAt: timestamp("created_at").defaultNow(),
  status: text("status").default("active"),
})

export const readingProgress = pgTable("reading_progress", {
  id: uuid("id").defaultRandom().primaryKey(),
  userId: uuid("user_id").notNull().references(() => users.id),
  bookId: uuid("book_id").notNull().references(() => books.id),
  currentPage: integer("current_page").default(0),
  percentCompleted: numeric("percent_completed", { precision: 5, scale: 2 }).default("0"),
  lastReadAt: timestamp("last_read_at"),
})

export const bookmarks = pgTable("bookmarks", {
  id: uuid("id").defaultRandom().primaryKey(),
  userId: uuid("user_id").notNull().references(() => users.id),
  bookId: uuid("book_id").notNull().references(() => books.id),
  pageNumber: integer("page_number").notNull(),
  note: text("note"),
  createdAt: timestamp("created_at").defaultNow(),
})