
--
-- AUTO_INCREMENT for table `campaign_logs`
--
ALTER TABLE `campaign_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `career_applications`
--
ALTER TABLE `career_applications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `career_jobs`
--
ALTER TABLE `career_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `case_studies`
--
ALTER TABLE `case_studies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `client_projects`
--
ALTER TABLE `client_projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_lists`
--
ALTER TABLE `contact_lists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_list_contacts`
--
ALTER TABLE `contact_list_contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contract_attachments`
--
ALTER TABLE `contract_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_templates`
--
ALTER TABLE `contract_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contract_versions`
--
ALTER TABLE `contract_versions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `daily_reports`
--
ALTER TABLE `daily_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_contents`
--
ALTER TABLE `featured_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feature_prices`
--
ALTER TABLE `feature_prices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_reports`
--
ALTER TABLE `financial_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_sliders`
--
ALTER TABLE `hero_sliders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `home_ctas`
--
ALTER TABLE `home_ctas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `home_features`
--
ALTER TABLE `home_features`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `home_processes`
--
ALTER TABLE `home_processes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `home_stats`
--
ALTER TABLE `home_stats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `industries`
--
ALTER TABLE `industries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leaders`
--
ALTER TABLE `leaders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `legal_pages`
--
ALTER TABLE `legal_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `navigation_items`
--
ALTER TABLE `navigation_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pm_projects`
--
ALTER TABLE `pm_projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pm_tasks`
--
ALTER TABLE `pm_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pm_time_logs`
--
ALTER TABLE `pm_time_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `press_releases`
--
ALTER TABLE `press_releases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_milestones`
--
ALTER TABLE `project_milestones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quote_items`
--
ALTER TABLE `quote_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salary_disbursements`
--
ALTER TABLE `salary_disbursements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_orders`
--
ALTER TABLE `service_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `shareholder_meetings`
--
ALTER TABLE `shareholder_meetings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_data`
--
ALTER TABLE `stock_data`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subsidiaries`
--
ALTER TABLE `subsidiaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_member_bank_accounts`
--
ALTER TABLE `team_member_bank_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `timelines`
--
ALTER TABLE `timelines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `virtual_accounts`
--
ALTER TABLE `virtual_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=623;

--
-- AUTO_INCREMENT for table `work_logs`
--
ALTER TABLE `work_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_conversations`
--
ALTER TABLE `ai_conversations`
  ADD CONSTRAINT `ai_conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ai_usage_logs`
--
ALTER TABLE `ai_usage_logs`
  ADD CONSTRAINT `ai_usage_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `campaign_logs`
--
ALTER TABLE `campaign_logs`
  ADD CONSTRAINT `campaign_logs_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_logs_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `career_applications`
--
ALTER TABLE `career_applications`
  ADD CONSTRAINT `career_applications_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `career_applications_career_job_id_foreign` FOREIGN KEY (`career_job_id`) REFERENCES `career_jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `case_studies`
--
ALTER TABLE `case_studies`
  ADD CONSTRAINT `case_studies_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD CONSTRAINT `chat_conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_projects`
--
ALTER TABLE `client_projects`
  ADD CONSTRAINT `client_projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_list_contacts`
--
ALTER TABLE `contact_list_contacts`
  ADD CONSTRAINT `contact_list_contacts_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contact_list_contacts_contact_list_id_foreign` FOREIGN KEY (`contact_list_id`) REFERENCES `contact_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contracts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contract_attachments`
--
ALTER TABLE `contract_attachments`
  ADD CONSTRAINT `contract_attachments_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contract_attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contract_templates`
--
ALTER TABLE `contract_templates`
  ADD CONSTRAINT `contract_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contract_versions`
--
ALTER TABLE `contract_versions`
  ADD CONSTRAINT `contract_versions_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contract_versions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD CONSTRAINT `daily_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `client_projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `navigation_items`
--
ALTER TABLE `navigation_items`
  ADD CONSTRAINT `navigation_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `navigation_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pm_projects`
--
ALTER TABLE `pm_projects`
  ADD CONSTRAINT `pm_projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pm_projects_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pm_tasks`
--
ALTER TABLE `pm_tasks`
  ADD CONSTRAINT `pm_tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pm_tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `pm_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pm_time_logs`
--
ALTER TABLE `pm_time_logs`
  ADD CONSTRAINT `pm_time_logs_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `pm_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pm_time_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `project_milestones`
--
ALTER TABLE `project_milestones`
  ADD CONSTRAINT `project_milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `client_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_converted_to_invoice_id_foreign` FOREIGN KEY (`converted_to_invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD CONSTRAINT `quote_items_feature_price_id_foreign` FOREIGN KEY (`feature_price_id`) REFERENCES `feature_prices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quote_items_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_disbursements`
--
ALTER TABLE `salary_disbursements`
  ADD CONSTRAINT `salary_disbursements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_orders`
--
ALTER TABLE `service_orders`
  ADD CONSTRAINT `service_orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_orders_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_orders_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  ADD CONSTRAINT `support_ticket_messages_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_member_bank_accounts`
--
ALTER TABLE `team_member_bank_accounts`
  ADD CONSTRAINT `team_member_bank_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `visitors`
--
ALTER TABLE `visitors`
  ADD CONSTRAINT `visitors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `work_logs`
--
ALTER TABLE `work_logs`
  ADD CONSTRAINT `work_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
