# Issue for ai_best_practices: Progressive context convention for d.o. issues

## Title

Skill: progressive context convention for issue descriptions and reviews

## Description

### Context

[#3585894] proposes an AGENTS.md for Drupal core that constrains LLM-generated prose: 180-word limit, simplified English, facts only. This is a harm reduction measure and it addresses a real problem. Unreviewed LLM output dumped into the issue queue creates an asymmetric burden — other humans end up cleaning it up, and that drain on contributors is not sustainable.

This skill does not replace or compete with that proposal. It picks up where it leaves off: if we're compressing the top layer (good), where does the displaced context go?

### Problem

There is no convention for where additional context should go when an issue description is kept short. Without one, contributors either stuff everything into the issue body (defeating the purpose of shorter descriptions) or leave out detail that reviewers need later. This is already a pain point for human reviewers who scan the queue — long issue bodies are hard to triage, but overly compressed ones lose the context that deep reviewers need.

### Proposed skill

A skill that guides contributors on structuring issue context by depth. This codifies what the existing d.o. issue summary template (Problem/Proposed Resolution/Remaining Tasks) already encourages, and extends it with explicit guidance for MR descriptions and attachments:

**Tier 1 — Issue body:** State the problem, the cause, and the fix direction. Keep it short enough to scan in the issue queue. This is what the existing template already asks for.

**Tier 2 — MR description:** When a merge request exists, describe what changed and why at the code level. Reference specific files and functions. Include test commands. This is the new convention — MR descriptions on d.o. are currently underused as a structured context layer.

**Tier 3 — Attachment:** When the change has non-obvious implications, attach a file with deeper technical context (subsystem interactions, performance data, prior discussion summaries). Note: verify that the attachment file type you use is not rendered inline on the issue page — the goal is progressive disclosure where the reviewer chooses to open the file.

### Who this helps

- Human reviewers scanning the queue read less noise at Tier 1 — the issue body stays scannable
- Human deep reviewers find structured detail at Tier 2 and 3 instead of digging through comment threads
- Contributors using AI tooling also benefit, since structured context is easier to load selectively than unstructured comment threads

### Related

- [#3581704] — issue etiquette guidance; this convention could be a companion section within it, or could stand alone as advisory guidance within ai_best_practices

### Attached

Draft convention document (tiered-context-convention.md)

---

Generated with the help of an LLM.
